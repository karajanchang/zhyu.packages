<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 2019-02-21
 * Time: 17:36
 */

namespace Zhyu\Repository\Criterias;

use Zhyu\Repository\Contracts\RepositoryInterface;


abstract class Criteria {

    /**
     * @param $model
     * @param RepositoryInterface $repository
     * @return mixed
     */
    public abstract function apply($model, RepositoryInterface $repository);
}