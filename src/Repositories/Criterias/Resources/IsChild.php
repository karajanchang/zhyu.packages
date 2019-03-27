<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 2019-03-27
 * Time: 17:39
 */

namespace Zhyu\Repositories\Criterias\Resources;

use Zhyu\Repositories\Contracts\RepositoryInterface;
use Zhyu\Repositories\Criterias\Criteria;


class IsChild extends Criteria
{
    public function apply($model, RepositoryInterface $repository)
    {
        $query = $model->whereNotNull('parent_id');
        return $query;
    }

}