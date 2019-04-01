<?php
namespace Zhyu\Controller;

use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Zhyu\Datatables\DatatablesFactoryApp;
use Zhyu\Controller\Controller as ZhyuController;
use Zhyu\Repositories\Eloquents\UserPermissionRepository;
use Zhyu\Repositories\Eloquents\UserRepository;
use Zhyu\Facades\ZhyuUrl;

class UserController extends ZhyuController
{

    protected $repository;

    public function __construct()
    {
        $this->middleware(['web', 'auth', 'checklogin']);
        $this->makeRepository();
    }

    public function repository(){

        return UserRepository::class;
    }


    private function makeRepository(){
        $repository = app()->make($this->repository());

        return $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $query = request()->input('query');
        if(!isset($query)){
        }
        $this->authorize('admin.users.index');
        $model = $this->repository->makeModel();
        $datatablesService = DatatablesFactoryApp::bind($this->table ? $this->table : $model->getTable());

        $obj = ZhyuUrl::decode($query);
        $title = isset($obj[2]) ? (string) $model->find($obj[2]).'<button type="button" onclick="location.href=\''.route('resources.index').'\'">返回</button>' : null;

        $this->setRoute('admin.users');

        return $this->view('index', $model, ['datatablesService' => $datatablesService, 'title' => $title]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('admin.users.create');

        return parent::view(null, $this->repository->makeModel());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('admin.users.create');

        $rules = method_exists($this, 'rules_edit') ? $this->rules_create() : $this->rules();
        $this->validate($request, $rules);
        $this->repository->create($request->all());

        return $this->responseJson('success');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->authorize('admin.users.create');

        try {
            $model = $this->repository->find($id);

            return parent::view(null, $model, ['title' => $title]);
        }catch (\Exception $e){

            return $this->responseJson($e, 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  App\Model  $logistic
     * @return \Illuminate\Http\Response
     */
    public function edit($id, $title = null)
    {
        $this->authorize('admin.users.edit');

        $model = $this->repository->find($id);

        return parent::view(null, $model, ['title' => $title]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Model  $logistic
     * @return \Illuminate\Http\Response
     */
    public function update($id, Request $request)
    {
        $this->authorize('admin.users.edit');

        $rules = method_exists($this, 'rules_edit') ? $this->rules_edit() : $this->rules();
        $this->validate($request, $rules);
        $this->repository->update($id, $request->all());

        return $this->responseJson('success');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('admin.users.destroy');

        $this->repository->delete($id);

        return $this->responseJson('success');
    }

    public function rules(){

        return [
            'parent_id' => ['nullable', 'numeric'],
            'name' => 'required',
            'route' => 'nullable',
            'orderby' => ['nullable', 'numeric'],
            'icon_css' => ['nullable', 'string'],
        ];
    }

    public function priv($id, UserPermissionRepository $permissionRepository){
        $model = $this->repository->find($id);
        $permissions = $permissionRepository->findWhere([
            'usergroup_id' => $id,
        ]);
        $return_url = route('admin.usergroups.index');

        return $this->view('priv', $model, ['title' => $model->name. ' 權限', 'table' => 'priv', 'permissions' => $permissions, 'return_url' => $return_url ]);
    }

    public function privSave($id, Request $request, UserPermissionRepository $permissionRepository){
        $this->validate($request, $this->rules_priv());

        $user = $this->repository->find($id);
        $all = $request->all();


        if($all['isin']==1) {
            $permissionRepository->create([
                'user_id' => $id,
                'act' => $all['act'],
                'resource_id' => $all['resource_id'],
            ]);
        }else{
            $permissionRepository->deleteWhere([
                'user_id' => $id,
                'act' => $all['act'],
                'resource_id' => $all['resource_id'],
            ]);
        }
        return 'success';
    }

    public function rules_priv(){
        return [
            'isin' => [ 'required', 'numeric' ],
            'act' => [ 'required', 'string' ],
            'resource_id' => ['required', 'numeric'],
        ];
    }
}