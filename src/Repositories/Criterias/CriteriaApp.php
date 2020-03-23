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
    //---for normal bind
    public static function bind(Repository $repository, $name){
        $criterias = config('criteria.'.$name);
        if(is_null($criterias) || count($criterias)==0){

            return ;
        }

        foreach($criterias as $criteria) {
            $repository->pushCriteria(new $criteria);
        }
    }
    //---for ajax bind
    public static function ajaxBind(Repository $repository, $name){
        $systems = ['resources.ajax'];

        $criterias = config('criteria.' . $name);

        if (is_null($criterias) || count($criterias) == 0) {
            $file = base_path('vendor/zhyu/packages/src/config/criteria.php');
            if(in_array($name, $systems) && file_exists($file)){
                $configs = include $file;
                $key = explode('.', $name);
                $criterias = $configs[$key[0]][$key[1]];
            }
            if (is_null($criterias) || count($criterias) == 0) {
                return;
            }
        }
        if(isset($criterias['select'])){
            $repository->setSelect($criterias['select']);
        }

        foreach($criterias as $key => $criteria) {
            if(strtolower($key)!='select'){
                if(!class_exists($criteria)) {
                    throw new \Exception('this criteria can not ininal: '.$criteria);
                }
                $repository->pushCriteria(new $criteria);
            }
        }
        static::ajax($repository);
    }

    public static function ajax(Repository $repository){
        $draw = request()->input('draw');
        $columns = request()->input('columns');
        $orders = request()->input('order');

        $parseColumns = $repository->getSelect(true);
//        dump($columns);
//        dump($orders);
//        dd($parseColumns);

        if(is_array($columns) && is_array($orders) && count($columns) && count($orders) && $draw){
            foreach($orders as $order) {
                $key = $order['column'];
                if(isset($columns[$key]['data'])) {
                    if(count($parseColumns)){
                        if(in_array($columns[$key]['data'], $parseColumns)){
                            $criteria = new OrderByCustom($columns[$key]['data'], $order['dir']);
                            $repository->pushCriteria($criteria);
                        }
                    }else {
                        $criteria = new OrderByCustom($columns[$key]['data'], $order['dir']);
                        $repository->pushCriteria($criteria);
                    }
                }
            }
        }
    }
}