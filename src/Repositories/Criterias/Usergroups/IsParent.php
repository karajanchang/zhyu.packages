<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 2019-03-27
 * Time: 17:39
 */

namespace Zhyu\Repositories\Criterias\Usergroups;

use Zhyu\Repositories\Contracts\RepositoryInterface;
use Zhyu\Repositories\Criterias\Criteria;


class IsParent extends Criteria
{
    public function apply($model, RepositoryInterface $repository)
    {
        $query = $model->where(function($query){
            $query->whereNull('parent_id')->OrWhere('parent_id', 0);
        });
        return $query;
    }

}