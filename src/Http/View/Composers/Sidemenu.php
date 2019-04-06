<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 2019-03-27
 * Time: 16:39
 */

namespace Zhyu\Http\View\Composers;

use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;
use Zhyu\Repositories\Criterias\Common\OrderByOrderbyDesc;
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
		$rows = Cache::remember(env('APP_TYPE').'ZhyuCompose', now()->addMinutes(60), function() {
			$isChild = new IsChild();
			$this->resourceRepository->pushCriteria($isChild);
			$orderByOrderbyDesc = new OrderByOrderbyDesc();
			$this->resourceRepository->pushCriteria($orderByOrderbyDesc);
			return $this->resourceRepository->with(['parent'])->all();
		});
		
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