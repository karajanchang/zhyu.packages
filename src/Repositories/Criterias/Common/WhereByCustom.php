<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 2019-03-01
 * Time: 21:05
 */

namespace Zhyu\Repositories\Criterias\Common;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use ParagonIE\Sodium\Core\Curve25519\Ge\P1p1;
use Zhyu\Repositories\Contracts\RepositoryInterface;
use Zhyu\Repositories\Criterias\Criteria;


class WhereByCustom extends Criteria
{
    private $columns;
    private $wheres = ['=', '>', '>=', '<', '<=', '!=', 'like'];
    public function __construct(array $columns)
    {
        $this->columns = $columns;
    }

    public function apply($model, RepositoryInterface $repository)
    {
        //dump($this->columns);
        $query = $model->where(function($query){
            foreach($this->columns as $columns){
                foreach($columns as $rcol => $column) {
                    $funcs = $this->func($rcol, $column);
                    foreach($funcs as $func => $cols) {
                        call_user_func_array([$query, $func], $cols);
                    }
                }
            }
        });
        return $query;
    }

    private function func(string $rcol, array $columns) : array{
        $ps = [DB::raw($rcol)];
        if(in_array($columns[0], $this->wheres)){
            foreach($columns as $co){
                array_push($ps, $co);
            }
            return [
                'where' => $ps
            ];
        }

        foreach($columns as $key => $co){
            if($key==0) continue;
            array_push($ps, $co);
        }

        return [
            $columns[0] => $ps
        ];
    }


}
