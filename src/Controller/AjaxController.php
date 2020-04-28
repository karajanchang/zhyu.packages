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
use Zhyu\Facades\ZhyuTool;
use Zhyu\Repositories\Contracts\RepositoryInterface;
use Zhyu\Repositories\Criterias\Common\OrWhereByCustom;
use Zhyu\Repositories\Criterias\Common\WhereByCustom;
use Zhyu\Repositories\Criterias\CriteriaApp;
use Zhyu\Repositories\Eloquents\Repository;
use Zhyu\Repositories\Eloquents\RepositoryApp;
use Zhyu\Controller\Controller as ZhyuController;

class AjaxController extends ZhyuController
{
    private $divide = '#';

    public function __construct()
    {
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

    private function search(RepositoryInterface $repository){
        $search = request()->input('search');
        if(!isset($search['value']) || is_null($search['value']) || mb_strlen($search['value'])<2) return ;

        $selectColumns = $repository->getSelect(true);

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

    private function query(RepositoryInterface $repository){
        $query = request()->input('query');
        if(is_null($query)){

            return ;
        }
        $cols = ZhyuTool::urlMakeQuery($this->divide)->decode($query);
        /*
        dd($p);
        $request_query = explode('*', $query);

        $cols = [];
        $oColumns = $repository->getSelect(false);
        $selectColumns = $repository->getSelect(true);

        if(count($request_query)) {
            foreach ($request_query as $query) {
                $params = explode($this->divide, $query);
                if(count($params)) {
                    $key = array_search($this->parseFristColumn($params[0]), $selectColumns);
                    if(isset($key) && $key>0 ) {
                        $col = $oColumns[$key];
                        array_shift($params);
                        //dump('params '.$col, $params);
                        if(isset($col)) {
                            //---看是不是array []
                            if(strstr($params[1], '[') && strstr($params[1], ']')){
                                $params[1] = str_replace('[', '', $params[1]);
                                $params[1] = str_replace(']', '', $params[1]);
                                $params[1] = explode(',', $params[1]);
                            }
                            array_push($cols, [$col, $params]);
                        }
                    }
                }
            }
        }
        */
        if(count($cols)) {
            $criteria = new WhereByCustom($cols);
            $repository->pushCriteria($criteria);
        }
//        $query = $repository->toSql();
//        dump($query);
//        $bindings = $repository->getBindings();
//        dump($bindings);
    }

    private function getRepository($model, $act) : Repository{
        $bindName = request()->input('bindName');
        if (!empty($bindName)) {
            $bindName = urldecode($bindName);
            $className = config('datatables.' . $bindName);
            $dtTable = app($className);
            $model = app($dtTable->model())->getTable();
            RepositoryApp::bind($model);
            $repository = app()->make(RepositoryInterface::class);
            CriteriaApp::ajaxBind($repository,$model . '.' . $act, $dtTable);
        } else {
            RepositoryApp::bind($model);
            $repository = app()->make(RepositoryInterface::class);
            CriteriaApp::ajaxBind($repository, $model . '.' . $act);
        }

        return $repository;
    }

    public function index($model, $act, $resource = null)
    {
        $repository = $this->getRepository($model, $act);

        $this->currentPage();
        $this->search($repository);
        $this->query($repository);

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
