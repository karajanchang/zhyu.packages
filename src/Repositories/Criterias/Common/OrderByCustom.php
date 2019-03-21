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


class OrderByCustom extends Criteria
{
    private $orderby_col;
    private $orderby_dir;
    public function __construct($orderby_col='', $orderby_dir='')
    {
        $this->orderby_col = $orderby_col;
        $this->orderby_dir = $orderby_dir;
    }

    public function apply($model, RepositoryInterface $repository)
    {
        $query = $model->orderby($this->orderby_col, $this->orderby_dir);
        return $query;
    }

}