<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 2019-02-21
 * Time: 17:36
 */

namespace Zhyu\Repositories\Criterias;

use Illuminate\Database\Eloquent\Builder;
use Zhyu\Repositories\Contracts\RepositoryInterface;


abstract class Criteria {

    /**
     * @param $model
     * @param RepositoryInterface $repository
     * @return mixed
     */
    public abstract function apply(Builder $model, RepositoryInterface $repository);

    protected function getTable(Builder $model){

        return $model->getModel()->getTable();
    }
}