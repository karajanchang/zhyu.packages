<?php
namespace Zhyu\Controller;

use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Zhyu\Datatables\DatatablesFactoryApp;
use Zhyu\Controller\Controller as ZhyuController;
use Zhyu\Repositories\Eloquents\ResourceRepository;
use Zhyu\Facades\ZhyuUrl;

class ResourceController extends ZhyuController
{

    protected $repository;

    public function __construct()
    {
        $this->middleware(['web', 'auth', 'checklogin']);
        $this->makeRepository();
        $this->setRoute('admin.resources');
    }

    public function repository(){

        return ResourceRepository::class;
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
            return redirect()->to('/admin/resources?query=parent_id:whereNull');
        }
        $this->authorize('admin.resources.index');
        $model = $this->repository->makeModel();
        $datatablesService = DatatablesFactoryApp::bind($this->table ? $this->table : $model->getTable());

        $obj = ZhyuUrl::decode($query);
        $title = isset($obj[2]) ? (string) $model->find($obj[2]).'<button type="button" onclick="location.href=\''.route('admin.resources.index').'\'">返回</button>' : null;

        return $this->view('index', ['datatablesService' => $datatablesService, 'title' => $title]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('superadmin-only');

        return parent::view(null, $this->repository->makeModel());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('superadmin-only');

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

            return parent::view(null, ['title' => $title]);
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
        $this->authorize('superadmin-only');

        return parent::view(null, ['title' => $title]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Model  $logistic
     * @return \Illuminate\Http\Response
     */
    public function update($id, Request $request)
    {
        $this->authorize('superadmin-only');

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
        $this->authorize('superadmin-only');

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


}