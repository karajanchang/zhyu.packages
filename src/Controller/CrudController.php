<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 2019-03-02
 * Time: 05:32
 */

namespace Zhyu\Controller;

use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use mysql_xdevapi\Exception;
use Zhyu\Datatables\DatatablesFactoryApp;
use Zhyu\Controller\Controller as ZhyuController;

abstract class CrudController extends ZhyuController
{

    protected $repository;

    public function __construct()
    {
        $this->middleware(['web', 'auth', 'checklogin']);
        $this->makeRepository();
        $this->makeRoute();
    }

    abstract public function repository();

    abstract public function rules($id = null);

    private function makeRepository(){
        $repository = app()->make($this->repository());
        return $this->repository = $repository;
    }

    private function makeRoute(){
        if(method_exists($this, 'route')){
            return ;
        }
        $route = $this->route();
        if(strlen($route)>0) {
            $this->setRoute($route);
        }
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize($this->getRoute().'.index');

        $model = $this->repository->makeModel();
        $datatablesService = DatatablesFactoryApp::bind($this->table ? $this->table : $model->getTable());

        return $this->view('index', ['datatablesService' => $datatablesService]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize($this->getRoute().'.create');

        return parent::view(null, $this->repository->makeModel());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize($this->getRoute().'.create');

        $rules = method_exists($this, 'rules_edit') ? $this->rules_create() : $this->rules();
        $this->validate($request, $rules);

        if(method_exists($this, 'filter_create')) {
            $all = $this->filter_create($request->all());
        }elseif(method_exists($this, 'filter')) {
            $all = $this->filter($request->all());
        }else{
            $all = $request->all();
        }

        $this->repository->create($all);

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
        $this->authorize($this->getRoute().'.index');

        try {
            $model = $this->repository->find($id);
            return parent::view(null, ['title' => (string) $model]);
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
        $this->authorize($this->getRoute().'.edit');

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
        $this->authorize($this->getRoute().'.edit');

        $rules = method_exists($this, 'rules_edit') ? $this->rules_edit($id) : $this->rules($id);

        $this->validate($request, $rules);

        if(method_exists($this, 'filter_edit')) {
            $all = $this->filter_edit($request->all());
        }elseif(method_exists($this, 'filter')) {
            $all = $this->filter($request->all());
        }else{
            $all = $request->all();
        }

        $this->repository->update($id, $all);

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
        $this->authorize($this->getRoute().'.destroy');

        $this->repository->delete($id);
        return $this->responseJson('success');
    }


}