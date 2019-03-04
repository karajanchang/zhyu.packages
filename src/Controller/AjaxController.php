<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 2019-03-02
 * Time: 05:32
 */

namespace Zhyu\Controller;

use Illuminate\Pagination\Paginator;
use Zhyu\Repositories\Contracts\RepositoryInterface;
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

        $currentPage = ($start / $limit) + 1;
        Paginator::currentPageResolver(function () use ($currentPage) {
            return $currentPage;
        });
    }

    public function index($model, $key, $limit = 50)
    {
        RepositoryApp::bind($model);
        $repository = app()->make(RepositoryInterface::class);
        CriteriaApp::ajaxBind($repository, $model.'.'.$key);

        $this->currentPage();
        $res = $repository->paginate($limit);
        //$repository->offset($start)->limit($limit);
        return [
            'draw' => intval(request()->input('draw')),
            'recordsTotal' =>  $res->total(),
            'recordsFiltered' => $res->total(),
            'data' => $res->items(),
        ];
    }
}