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


class OrWhereByCustom extends Criteria
{
    private $columns;
    public function __construct(array $columns)
    {
        $this->columns = $columns;
    }

    public function apply($model, RepositoryInterface $repository)
    {
        $query = $model->where(function($query){
            foreach($this->columns as $columns){
                call_user_func_array([$query, 'OrWhere'], $columns);
                //$query->orWhere($this->column);
            }
        });
        return $query;
    }
}