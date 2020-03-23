<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 2019-03-01
 * Time: 21:05
 */

namespace Zhyu\Repositories\Criterias\Common;

use Zhyu\Repositories\Contracts\RepositoryInterface;
use Zhyu\Repositories\Criterias\Criteria;


class OrderByOrderbyAsc extends Criteria
{
    public function apply($model, RepositoryInterface $repository)
    {
        $table_name = $this->getTable($model);
        $query = $model->orderby($table_name.'.orderby', 'asc');
        return $query;
    }

}