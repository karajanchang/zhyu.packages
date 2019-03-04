<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 2019-03-01
 * Time: 22:36
 */

namespace Zhyu\Repositories\Criterias;

use Zhyu\Repositories\Criterias\Common\OrderByCustom;
use Zhyu\Repositories\Eloquents\Repository;


class CriteriaApp{
    public static function bind(Repository $repository, $name){
        $criterias = config('criteria.'.$name);
        if(is_null($criterias) || count($criterias)==0){
            return ;
        }

        static::ajax($repository);
        foreach($criterias as $criteria) {
            $repository->pushCriteria(new $criteria);
        }
    }

    public static function ajax(Repository $repository){
        $draw = request()->input('draw');
        $columns = request()->input('columns');
        $orders = request()->input('order');

        if(count($columns) && count($orders) && $draw){
            foreach($orders as $order) {
                $key = $order['column'];
                if(isset($columns[$key]['data'])) {
                    $criteria = new OrderByCustom($columns[$key]['data'], $order['dir']);
                    $repository->pushCriteria($criteria);
                }
            }
        }
    }
}