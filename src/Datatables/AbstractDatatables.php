<?php

namespace Zhyu\Datatables;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

abstract class AbstractDatatables
{
    private $tableCreate;
    private $jsCreate;
    private $qa = null;
    public $model;

    protected $values = [];

    public function __construct(TableCreate $tableCreate, JsCreate $jsCreate)
    {
        $this->tableCreate = $tableCreate;
        $this->jsCreate = $jsCreate;
    }

    public function table()
    {
        $config = $this->config();
        $this->tableCreate->model($this->model());
        return $this->tableCreate->init($config);
    }

    public function js($varName = 'table')
    {
        $this->jsCreate->model($this->model());
        $config = $this->config();
        return $this->jsCreate->init($config, $varName);

    }
    abstract public function model();


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


}


