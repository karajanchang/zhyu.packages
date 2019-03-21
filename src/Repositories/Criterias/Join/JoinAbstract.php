<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 2019-03-20
 * Time: 23:55
 */

namespace Zhyu\Repositories\Criterias\Join;


use Zhyu\Repositories\Criterias\Criteria;
use Zhyu\Repositories\Contracts\RepositoryInterface;

abstract class JoinAbstract extends Criteria
{
    private static $origin_model = null;
    protected $join_model;
    protected $custom_foreign_key = null;

    public function __construct()
    {
        $this->makeJoinModel();
    }
    public static function originModel($model){
        if(self::$origin_model===null){
            self::$origin_model = $model;
        }
        return self::$origin_model;
    }

    public function getJoinModel(){
        return $this->makeJoinModel();
    }
    public function makeJoinModel(){
        $joinModel = app()->make($this->joinModel());
        return $this->join_model = new $joinModel;
    }

    abstract function joinModel();

    public function apply($model, RepositoryInterface $repository)
    {
        $origin_model = self::originModel($model);

        $table = $origin_model->getTable();
        $join_table = $this->join_model->getTable();

        $foreign_key = $join_table.'_id';
        if(!is_null($this->custom_foreign_key)){
            $foreign_key = $this->custom_foreign_key;
        }

        $query = $model->join($join_table, $table.'.'.$foreign_key, '=', $join_table.'.id');
        return $query;
    }

}