<?php
namespace Zhyu\Controller;

use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Log;
use Zhyu\Datatables\DatatablesFactoryApp;
use Zhyu\Controller\Controller as ZhyuController;
use Zhyu\Repositories\Eloquents\UsergroupPermissionRepository;
use Zhyu\Repositories\Eloquents\UsergroupRepository;
use Zhyu\Facades\ZhyuUrl;

class UsergroupController extends ZhyuController
{

    protected $repository;

    public function __construct()
    {
        $this->middleware(['web', 'auth', 'checklogin']);
        $this->makeRepository();
        $this->setRoute('admin.usergroups');
    }

    public function repository(){

        return UsergroupRepository::class;
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
            return redirect()->to(route('admin.usergroups.index').'?query=parent_id:=:0');
        }
        $this->authorize('admin.usergroups.index');
        $model = $this->repository->makeModel();
        $name = $this->table ? $this->table : $model->getTable();
        $datatablesService = DatatablesFactoryApp::bind($name);

        $obj = ZhyuUrl::decode($query);
        $title = isset($obj[2]) ? (string) $model->find($obj[2]) : null;

        return $this->view('index', $model, ['datatablesService' => $datatablesService, 'title' => $title]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('admin.usergroups.create');

        return parent::view(null, $this->repository->makeModel());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('admin.usergroups.create');

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
        $this->authorize('superadmin-only');

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
        $this->authorize('admin.usergroups.edit');

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
        $this->authorize('admin.usergroups.edit');

        $rules = method_exists($this, 'rules_edit') ? $this->rules_edit($id) : $this->rules($id);
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
        $this->authorize('admin.usergroups.destroy');

        $can_not_delete_ids = explode(',', env('ZHYU_ADMIN_GROUP_CAN_NOT_DELETE'));
        if(is_array($can_not_delete_ids)) {
            abort_if(in_array($id, $can_not_delete_ids), 423);
        }

        $this->repository->delete($id);

        return $this->responseJson('success');
    }

    public function rules($id = null){
        $unique = is_null($id) ? 'unique:usergroup,name' : 'unique:usergroup,name,'.$id;

        return [
            'parent_id' => ['nullable', 'numeric'],
            'name' => ['required', $unique],
            'is_online' => ['nullable', 'numeric'],
            'nologin' => ['nullable', 'numeric'],
        ];
    }

    public function priv($id, UsergroupPermissionRepository $permissionRepository){
        $model = $this->repository->find($id);
        $permissions = $permissionRepository->findWhere([
            'usergroup_id' => $id,
        ]);
        $return_url = route('admin.usergroups.index');

        return $this->view('priv', $model, ['title' => $model->name. ' 權限', 'table' => 'priv', 'permissions' => $permissions, 'return_url' => $return_url ]);
    }

    public function privSave($id, Request $request, UsergroupPermissionRepository $permissionRepository){
        $this->validate($request, $this->rules_priv());

        //$usergroup = $this->repository->find($id);
        $all = $request->all();


        if($all['isin']==1) {
            $permissionRepository->create([
                'usergroup_id' => $id,
                'act' => $all['act'],
                'resource_id' => $all['resource_id'],
            ]);
        }else{
            $permissionRepository->deleteWhere([
                'usergroup_id' => $id,
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