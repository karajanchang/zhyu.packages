<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 2019-03-27
 * Time: 16:39
 */

namespace Zhyu\Http\View\Composers;

use Illuminate\View\View;
use Zhyu\Repositories\Criterias\Common\OrderByOrderbyDesc;
use Zhyu\Repositories\Criterias\Common\WhereByCustom;
use Zhyu\Repositories\Criterias\Resources\IsChild;
use Zhyu\Repositories\Eloquents\ResourceRepository;

class Sidemenu
{
    public function __construct(ResourceRepository $resourceRepository)
    {
        $this->resourceRepository = $resourceRepository;
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view){
        $isChild = new IsChild();
        $this->resourceRepository->pushCriteria($isChild);

        $orderByOrderbyDesc = new OrderByOrderbyDesc();
        $this->resourceRepository->pushCriteria($orderByOrderbyDesc);

        $rows = $this->resourceRepository->with(['parent'])->all();

        $parents = [];
        $children = [];
        $rows->map(function($row, $d) use(&$parents, &$children){
            $parent = $row->parent;
            $parents[$row->parent->id] = $row->parent;
            $children[$parent->id][] = $row;
        });
        $parents = collect($parents)->sortByDesc('orderby');
        $children = $rows;
        $view->with('parents', $parents);
        $view->with('children', $children);
    }


}