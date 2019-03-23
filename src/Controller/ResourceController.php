<?php
namespace Zhyu\Controller;

use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Zhyu\Datatables\DatatablesFactoryApp;
use Zhyu\Controller\Controller as ZhyuController;
use Zhyu\Repositories\Eloquents\ResourceRepository;

class ResourceController extends ZhyuController
{
	
	protected $repository;
	
	public function __construct()
	{
		$this->middleware(['web', 'auth', 'checklogin']);
		$this->makeRepository();
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
		$model = $this->repository->makeModel();
		$datatablesService = DatatablesFactoryApp::bind($this->table ? $this->table : $model->getTable());
		return $this->view('index', $model, ['datatablesService' => $datatablesService]);
	}
	
	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		return parent::view(null, $this->repository->makeModel());
	}
	
	/**
	 * Store a newly created resource in storage.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
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
		$this->repository->delete($id);
		return $this->responseJson('success');
	}
	
	public function rules(){
		return [
			'name' => 'required',
			'route' => 'required',
		];
	}
	
	
}