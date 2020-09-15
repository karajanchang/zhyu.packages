<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 2019-03-01
 * Time: 21:05
 */

namespace Zhyu\Repositories\Criterias\Common;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use ParagonIE\Sodium\Core\Curve25519\Ge\P1p1;
use Zhyu\Repositories\Contracts\RepositoryInterface;
use Zhyu\Repositories\Criterias\Criteria;


class WhereByCustom extends Criteria
{
    private $columns;
    private $allColumns = [];
    private $query;

    private $wheres = ['=', '>', '>=', '<', '<=', '!=', 'like'];


    public function __construct(array $columns)
    {
        $this->columns = $columns;
    }

    public function apply($model, RepositoryInterface $repository)
    {
        $query = $model->where(function($query){
            $this->setQuery($query);
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

    /**
     * @return mixed
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * @param mixed $query
     */
    public function setQuery(Builder $query): void
    {
        $this->query = $query;
    }



    private function getThisTable(string $table = null){
        if(!is_null($table)){

            return $table;
        }
        $table = $this->getTable($this->query);

        return $table;
    }

    private function getAllTableColumns(string $table = null){
        $table = $this->getThisTable($table);
        if(isset($this->allColumns[$table])){

            return $this->allColumns[$table];
        }

        $this->allColumns[$table] = Schema::getColumnListing($table);

        return $this->allColumns[$table];
    }

    private function getTableAndColumnFromDot(string $rcol){
        $rcols = explode('.', $rcol);
        $realColumn = $rcols[(count($rcols)-1)];
        if(count($rcols)==1){

            return [
                'table' => null,
                'column' => $realColumn,
            ];
        }

        return [
            'table' => $rcols[0],
            'column' => $realColumn,
        ];
    }

    private function combine2Col($table, $column, array $params){
        $func = $params[2];
        $rColumn = is_null($params[3]) ? $column : $params[3];
        $col = '';
        if(!is_null($func)){
            $col = $func.'('.$rColumn.')';
        }else{
            $col = strlen($table)>0 ? $table . '.' . $rColumn : $rColumn;
        }
        $ps = [DB::raw($col)];

        return $ps;
    }

    private function func(string $rcol, array $columns) : array{
        $res = $this->getTableAndColumnFromDot($rcol);
        $allColumns = $this->getAllTableColumns($res['table']);
        $ps = $this->combine2Col($res['table'], $res['column'], $columns);

        if(in_array($columns[0], $this->wheres)){
            foreach($columns as $key => $co){
                if($key>1) break;
                array_push($ps, $co);
            }
            return [
                'where' => $ps
            ];
        }

        foreach($columns as $key => $co){
            if($key==0) continue;
            if($key>1) break;
            array_push($ps, $co);
        }

        return [
            $columns[0] => $ps
        ];
    }


}
