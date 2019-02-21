<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 2019-02-21
 * Time: 17:36
 */

namespace Zhyu\Repositories\Criterias;

use Zhyu\Repositories\Contracts\RepositoryInterface;


abstract class Criteria {

    /**
     * @param $model
     * @param RepositoryInterface $repository
     * @return mixed
     */
    public abstract function apply($model, RepositoryInterface $repository);
}