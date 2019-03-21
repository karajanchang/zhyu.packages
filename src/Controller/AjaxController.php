<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 2019-03-02
 * Time: 05:32
 */

namespace Zhyu\Controller;

use App\Driver;
use App\Task;
use Illuminate\Pagination\Paginator;
use Zhyu\Repositories\Contracts\RepositoryInterface;
use Zhyu\Repositories\Criterias\Common\OrWhereByCustom;
use Zhyu\Repositories\Criterias\CriteriaApp;
use Zhyu\Repositories\Eloquents\RepositoryApp;
use Zhyu\Controller\Controller as ZhyuController;

class AjaxController extends ZhyuController
{

    public function __construct()
    {
        $this->middleware(['web', 'auth', 'checklogin']);
    }

    private function currentPage(){
        $start = request()->input('start');
        $start = is_null($start) ? 0 : $start;
        $start = (int) $start;

        $limit = request()->input('length');
        $limit = is_null($limit) ? 50 : $limit;
        $limit = (int) $limit;
        $this->setLimit($limit);

        $currentPage = ($start / $limit) + 1;
        Paginator::currentPageResolver(function () use ($currentPage) {
            return $currentPage;
        });
    }

    private function search(RepositoryInterface $repository){
        $search = request()->input('search');
        if(!isset($search['value']) || is_null($search['value']) || mb_strlen($search['value'])<2){
            return ;
        }
//        $columns = request()->input('columns');

        $oColumns = $repository->getSelect(false);
        $selectColumns = $repository->getSelect(true);

        $cols = [];
        foreach($selectColumns as $key => $column){
            $col = $oColumns[$key];
            array_push($cols, [$col, 'like binary', '%' . $search['value'] . '%']);
        }
        if(count($cols)) {
            $criteria = new OrWhereByCustom($cols);
            $repository->pushCriteria($criteria);
        }
    }

    public function index($model, $key, $limit = 50)
    {
        RepositoryApp::bind($model);
        $repository = app()->make(RepositoryInterface::class);

        CriteriaApp::ajaxBind($repository, $model.'.'.$key);

        $this->currentPage();
        $this->search($repository);

        $res = $repository->paginate($this->getLimit());


        //--wrap data
        $rname = str_replace('_', '', ucwords($model, '_'));
        $cname = '\App\Http\Resources\\'.$rname.'Collection';

        try{
            if(file_exists(app_path('Http/Resources/'.$rname.'Collection.php'))) {
                $res = new $cname($res);
            }
        }catch(\Exception $e){
            throw new \Exception(' Resource initial fail: '.$cname);
        }
        return $res;
    }
}