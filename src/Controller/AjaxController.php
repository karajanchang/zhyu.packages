<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 2019-03-02
 * Time: 05:32
 */

namespace Zhyu\Controller;


class AjaxController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'checklogin']);
    }

    public function index($model, $key, $per_page_nums = 50)
    {
        RepositoryApp::bind($model);
        $repository = app()->make(RepositoryInterface::class);
        CriteriaApp::bind($repository, $model.'.'.$key);
        $rows = $repository->all();
        return $rows;
    }
}