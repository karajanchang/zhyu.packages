<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 2019-03-02
 * Time: 05:32
 */

namespace Zhyu\Controller;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Zhyu\Repositories\Contracts\RepositoryInterface;
use Zhyu\Repositories\Criterias\Common\OrderByCustom;
use Zhyu\Repositories\Criterias\Common\OrWhereByCustom;
use Zhyu\Repositories\Criterias\Common\WhereByCustom;
use Zhyu\Repositories\Criterias\CriteriaApp;
use Zhyu\Repositories\Eloquents\Repository;
use Zhyu\Repositories\Eloquents\RepositoryApp;
use Zhyu\Controller\Controller as ZhyuController;

class AjaxController extends ZhyuController
{
    private $divide = '#';
    private $is_new_api = true;
    private $datatable = [];

    public function __construct()
    {
        parent::__construct();
        $this->middleware(['web', 'auth', 'checklogin']);
    }

    private function currentPage(){
        $start = request()->input('start');
        $start = is_null($start) ? 0 : (int) $start;

        $limit = request()->input('length');
        $limit = is_null($limit) ? 50 : (int) $limit;
        $this->setLimit($limit);

        $currentPage = ($start / $limit) + 1;
        Paginator::currentPageResolver(function () use ($currentPage) {

            return $currentPage;
        });
    }

    private function oldDatatableAllColumns(Repository $repository) : array{
        //$model = app($repository->getModel());
        $table = $repository->getModel()->getTable();

        return Schema::getColumnListing($table);
    }

    private function search(RepositoryInterface &$repository){
        $search = request()->input('search');
        if(!isset($search['value']) || is_null($search['value']) || mb_strlen($search['value'])<2) return ;

        $selectColumns = $repository->getSelect(true);
        $first_key = array_key_first($selectColumns);

        //---old版本dattable
        if($first_key=='*'){
            $selectColumns = [];
            $columns = request()->input('columns');
            $allColumns = $this->oldDatatableAllColumns($repository);
            if(is_array($columns)) {
//                dd($first_key, $allColumns, $repository->model(), $columns, $this->is_new_api);
                foreach ($columns as $key => $cols) {
                    if(!in_array($cols['data'], $allColumns)) continue;
                    $selectColumns[$cols['data']] = $cols['data'];
                }
            }
        }

        $cols = [];
        foreach($selectColumns as $key => $column){
            array_push($cols, [ DB::raw($key), 'like binary', '%' . $search['value'] . '%']);
        }
        if(count($cols)) {
            $criteria = new OrWhereByCustom($cols);
            $repository->pushCriteria($criteria);
        }

    }


    /*
    private function parseFristColumn($param){
        $p = explode('.', $param);
        $last = count($p) - 1;

        return $p[$last];
    }
    */

    private function query(RepositoryInterface &$repository){
        $query = request()->input('query');

        if(is_null($query)){

            return ;
        }
        //$selectColumns = $repository->getSelect(true);
        $cols = [];
        if(is_array($this->query)){
            foreach($this->query as $key => $query){
                array_push($cols, [ $key => $query]);
            }
        }

        if(count($cols)) {
            if($this->is_new_api===false) {
                $criteria = new WhereByCustom($cols);
                $repository->pushCriteria($criteria);
            }else{
                $criteria = new WhereByCustom($cols);
                $repository->pushCriteria($criteria);
            }
        }
        $query = $repository->toSql();
        //dd($query);
        $bindings = $repository->getBindings();
        //dump($bindings);
    }

    private function order(RepositoryInterface $repository){
        $order = request()->input('order');

        $cols = [];
        if(!isset($this->datatable['config'])){

            return ;
        }
        if(count($order)) {
            if (is_array($this->datatable['config']['cols_display'])) {
                foreach ($this->datatable['config']['cols_display'] as $field => $cols_display) {
                    $cols[] = $field;
                }
            }

            foreach ($order as $orderby) {
                if (isset($orderby['column']) && isset($orderby['dir'])) {
                    if (isset($cols[$orderby['column']])) {
                        $col = $cols[$orderby['column']];
                        $criteria = new OrderByCustom($col, $orderby['dir']);
                        $repository->pushCriteria($criteria);
                    }
                }
            }
        }else{
            $criteria = new OrderByCustom($this->datatable['config']['default_order_by'], $this->datatable['config']['default_order_by']);
            $repository->pushCriteria($criteria);
        }
    }

    private function getRepository($model, $act) : Repository{
        $bindName = request()->input('bindName');

        $this->is_new_api = false;

        if (!empty($bindName)) {
            $bindName = urldecode($bindName);
            $className = config('datatables.' . $bindName);
            if (!is_null($className)) {
                $dtTable = app($className);
                $this->datatable['config'] = $dtTable->config();
                $model = app($dtTable->model())->getTable();
                RepositoryApp::bind($model);
                $repository = app()->make(RepositoryInterface::class);
                CriteriaApp::ajaxBind($repository, $model . '.' . $act, $dtTable);

                $this->is_new_api = true;

                return $repository;
            }
        }

        RepositoryApp::bind($model);
        $repository = app()->make(RepositoryInterface::class);
        CriteriaApp::ajaxBind($repository, $model . '.' . $act);

        return $repository;
    }

    public function index($model, $act, $resource = null)
    {
        DB::connection()->enableQueryLog();
        $repository = $this->getRepository($model, $act);

        $this->currentPage();
        $this->search($repository);
        $this->query($repository);
        $this->order($repository);

        $res = $repository->paginate($this->getLimit());



        //--wrap data
        $resource_name = is_null($resource) ? $model : $resource;
        $rname = str_replace('_', '', ucwords($resource_name, '_'));
        $cname = '\App\Http\Resources\\'.$rname.'Collection';
        $cname2 = '\Zhyu\Http\Resources\\'.$rname.'Collection';

        //dump('model: '.$model.', act: '.$act.', resource: '.$resource.', $rname: '.$rname);
        try{
            if(file_exists(app_path('Http/Resources/'.$rname.'Collection.php'))) {
                $res = new $cname($res);

                return $res;
            }elseif(file_exists(base_path('vendor/zhyu/packages/src/Http/Resources/'.$rname.'Collection.php'))){
                $res = new $cname2($res);

                return $res;
            }else{

            }

        }catch(\Exception $e){
            throw new \Exception(' Resource initial fail: '.$cname);
        }

        $total = $res->total();

        return [
            'draw' => request()->input('draw'),
            'recordsTotal' => $total,
            'recordsFiltered' => $total,
            'data' =>  $res->items(),
        ];

    }
}
