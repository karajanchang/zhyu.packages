<?php

namespace Zhyu\Datatables;

abstract class AbstractDatatables
{
    private $tableCreate;
    private $jsCreate;
    public $model;
    public $ajax;
    private $bindName;

    protected $values = [];

    public function __construct(TableCreate $tableCreate, JsCreate $jsCreate)
    {
        $this->tableCreate = $tableCreate;
        $this->jsCreate = $jsCreate;
        $this->makeModel();
    }

    public function setBindName(string $bindName){
        $this->bindName = urlencode($bindName);

        return $this;
    }

    public function getBindName(){

        return $this->bindName;
    }

    private function makeModel(){
        $this->model = app()->make($this->model());
    }

    private function makeModelAct(){
        $act = strtolower($this->act());
        if(isset($act) && strlen($act)>0){

            return [
                'model' => $this->model->getTable(),
                'act' => $act,
                'resource' => $this->resource(),
                'bindName' => $this->getBindName(),
            ];
        }

        return [
            'model' => $this->model->getTable(),
            'act' => 'ajax',
            'resource' => $this->resource(),
            'bindName' => $this->getBindName(),
        ];
    }

    public function table()
    {
        $config = $this->config();
        $this->tableCreate->model($this->model);

        return $this->tableCreate->init($config);
    }

    public function js($varName = 'table')
    {
        $this->jsCreate->model($this->model);
        $config = $this->config();

        return $this->jsCreate->init($config, $varName, $this->makeModelAct());

    }

    //---if want custom url, override this function
    /*
    public function ajax(){
        return null;
    }*/
    abstract public function model();
    abstract public function act();

    //---if want custom url, override this function
    public function resource(){
        return null;
    }


    /*
    private function getDbCols(){
        $config = $this->config();
        $rcol = [];
        if(isset($config['cols']) && count($config['cols'])){
            foreach($config['cols'] as $key => $col){
                if(is_int($key)) {
                    $rcol[] = $col;
                }else{
                    $rcol[] = DB::raw("$col as $key");
                }
            }
        }
        return $rcol;
    }

    private function getOrderbyCols(){
        $config = $this->config();
        $rcol = [];
        if(isset($config['cols']) && count($config['cols'])){
            foreach($config['cols'] as $key => $col){
                if(is_int($key)) {
                    $rcol[] = $col;
                }else{
                    $rcol[] = $key;
                }
            }
        }
        return $rcol;
    }
    */

}

