<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 2019-03-02
 * Time: 05:31
 */

namespace Zhyu\Controller;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Zhyu\Repositories\Eloquents\RepositoryApp;
use Illuminate\Support\Facades\Log;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $title = null;
    protected $id = null;
    protected $table = null;
    protected $route = null;


    protected $columns;
    protected $limit;

    protected $model;

    public function __construct()
    {
        RepositoryApp::bind((new \ReflectionClass($this))->getShortName());
    }

    /**
     * @return mixed
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * @param mixed $columns
     */
    public function setColumns($columns)
    {
        $this->columns = $columns;
    }


    /**
     * @return mixed
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @param mixed $limit
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;
    }

    /**
     * @return null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param null $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getTable(): string
    {
        return $this->table;
    }

    /**
     * @param string $table
     */
    public function setTable(string $table): void
    {
        $this->table = $table;
    }

    /**
     * @return null
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * @param null $route
     */
    public function setRoute($route): void
    {
        $this->route = $route;
    }

    public function returnClassBaseName($class){
        return  strtolower(class_basename($class));
    }

    protected function view($view = 'index', $params = null){
        $model = null;
        $model_name = $this->returnClassBaseName($model);
        ${$model_name} = $model;

        $title = $this->titleFromModelOrParams($model, $params);

        if(isset($params['table']) && strlen($params['table'])>0){
            $table = $params['table'];
        }else{
            if(!is_null($this->table)) {
                $table = $this->table;
            }else{
                if(!is_null($model)) {
                    $table = $model->getTable();
                }
            }
        }
        if(!isset($table)){
            //throw new \Exception('please provide table name first!!!');
        }

        $route = $this->getRoute();
        if(is_null($route)){
            $route = $table;
        }

        $id = null;
        if(isset($model->id)){
            $id = $model->id;
        }

        $datatablesService = null;
        if(isset($params['datatablesService'])){
            $datatablesService = $params['datatablesService'];
        }
        if($view == 'index'){
            $view = 'vendor.zhyu.index';
        }
        $table = null;
        $addOrUpdateUrl = $this->getAddOrUpdateUrl($model, $table, $route);

        $compacts = compact('route','table', 'title', 'id', 'datatablesService', 'model_name', $model_name, 'addOrUpdateUrl');
        $returns = $params;
        foreach($compacts as $key => $val){
            $returns[$key] = $val;
        }

        return view()->first([$view, 'vendor.zhyu.form'], $returns);
    }

    private function titleFromModelOrParams(Model $model = null, array $params) : string{
        if(!empty($params['title'])){
            $this->setTitle($params['title']);

            return $params['title'];
        }

        try {
            $title = (string) $model;
            $this->setTitle($title);

            return $title;
        }catch(\Exception $e){
            $title = '';
        }

        return $title;
    }

    public function responseJson($message, $status = 200){
        if($message instanceof \Exception){
            $message = $message->getMessage();
        }

        return response()->json([ 'message' => $message ], $status);
    }

    private function getAddOrUpdateUrl($model, $table = null, $route) : string {
        $addOrUpdateUrl = '';
        if(!empty($model->id)){
            try{
                $addOrUpdateUrl = route($route.'.update', [ $table => $model->id, $table => $model]);
            }catch (\Exception $e){
                $name = substr($table, 0, (strlen($table)-1) );
                try{
                    $addOrUpdateUrl = route($route.'.update', [ $name => $model->id, $table => $model]);
                }catch (\Exception $e){
                    Log::error('Zhyu Controller error: ', [$e]);
                }
            }
        }else{
            try{
                $addOrUpdateUrl = route($route.'.store');
            }catch (\Exception $e){
            }
        }

        return $addOrUpdateUrl;
    }
}
