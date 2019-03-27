<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 2019-03-01
 * Time: 21:05
 */

namespace Zhyu\Repositories\Criterias\Common;

use Illuminate\Support\Arr;
use Zhyu\Repositories\Contracts\RepositoryInterface;
use Zhyu\Repositories\Criterias\Criteria;


class WhereByCustom extends Criteria
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
                if(is_array($columns)) {
                    if(count($columns[1])==1){
                        $func = array_pop($columns[1]);
                        unset($columns[1]);
                        call_user_func_array([$query, $func], $columns);
                    }else {
                        $columns = Arr::flatten($columns);
                        call_user_func_array([$query, 'Where'], $columns);
                    }
                }
            }
        });
        return $query;
    }
}