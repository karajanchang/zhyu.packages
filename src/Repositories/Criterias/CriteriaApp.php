<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 2019-03-01
 * Time: 22:36
 */

namespace Zhyu\Repositories\Criterias;

use Zhyu\Repositories\Eloquents\Repository;


class CriteriaApp{
    public static function bind(Repository $repository, $name){
        $criterias = config('criteria.'.$name);
        if(is_null($criterias) || count($criterias)==0){
            return ;
        }
        foreach($criterias as $criteria) {
            $repository->pushCriteria(new $criteria);
        }
    }
}