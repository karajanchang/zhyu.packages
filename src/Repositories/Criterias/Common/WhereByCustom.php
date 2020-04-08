<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 2019-03-01
 * Time: 21:05
 */

namespace Zhyu\Repositories\Criterias\Common;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
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
            foreach($this->columns as $col => $columns){
                //dump($columns);
                if(is_array($columns)) {
                    //$func = $columns[0]=='=' ? 'where' : $columns[0];
                    $func = $this->func($query, $col, $columns);
//                    dump('func func---start');
//                    dump($func);
//                    dump('func func---end');
                    try {
                        call_user_func_array([$query, $func[0]], $func[1]);
                    }catch (\Exception $e){

                        Log::error('WhereByCustom class ERROR: ', [$func, $e->getMessage()]);
                    }
                }else{
//                    dump('bbbbbbbbbbbbb');
//                    dump($col, $columns);
                    call_user_func_array([$query, 'where'], [ $col, $columns]);
                }
            }
        });
//        dump($query->toSql());
//        dump($query->getBindings());

        return $query;
    }

    private function func($query, $col, array $columns){
        $wheres = ['=', '>', '>=', '<', '<=', '!=', 'like'];

        if(in_array($columns[0], $wheres)){
//            dump('ininininininininini');
//            dump($columns[0]);
            return [
                'where', [
                    $col, $columns[0], $columns[1]
                ],
            ];
        }
        //dd($columns, $col);
        return [$columns[0], [
            $col, $columns[1]
        ],
        ];
    }
}