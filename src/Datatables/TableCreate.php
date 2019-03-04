<?php

namespace Zhyu\Datatables;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Lang;

class TableCreate {
    private $id;
    private $css;
    private $cols_display = [];
    private $table = '';
    private $model;

    public function model(Model $model){
        return $this->model = $model;
    }
    public function init($config){
        if(isset($config['id'])){
            $this->id = $config['id'];
        }
        if(isset($config['css'])){
            $this->css = $config['css'];
        }
        if(isset($config['cols_display'])){
            $this->cols_display = $config['cols_display'];
        }
        $this->head();
        $this->body();

        return $this->table;
    }
    private function head(){
        $this->table = '<table id="'.$this->id.'" '.$this->css($this->css).'>';
    }

    private function body(){
        $this->table.='<thead>';
        $this->table.='<tr>';
        $this->table.=$this->cols();
        $this->table.='</tr>';
        $this->table.='</thead>';
        $this->table.='<tbody>';
        $this->table.='</tbody>';
        $this->table.='</table>';
    }
    private function cols(){
        $tableName = $this->model->getTable();
        $str='';
        if(count($this->cols_display)){
            foreach($this->cols_display as $cols){
                $css = isset($cols['css']) ? $this->css($cols['css']) : '';
                $title = $tableName . '.' . $cols['title'];
                if(Lang::has($title)) {
                    $str.='<th '.$css.'>'.trans($title).'</th>';
                }else{
                    $str.='<th '.$css.'>'.$cols['title'].'</th>';
                }
            }
        }
        return $str;
    }


    private function css($css){
        if(is_string($css)){
            return ' class="'.$css.'"';
        }
        if(is_array($css)){
            return ' class="'.join(' ', $css).'"';
        }
    }
}