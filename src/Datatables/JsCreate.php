<?php

namespace Zhyu\Datatables;

use Illuminate\Database\Eloquent\Model;

class JsCreate {
    private $id;
    private $ajax;
    private $js;
    private $cols_display = [];
    private $no_orderable_cols = [];
    private $render;
    private $model;

    public function __construct(Render $render)
    {
        $this->render = $render;
    }
    public function model(Model $model){
        return $this->model = $model;
    }

    public function init($config, $varName = 'table', array $model_act){
        if(isset($config['id'])){
            $this->id = $config['id'];
        }

        if(!is_array($model_act)){
            throw new \Exception('Please set act() in datables Class');
        }

        if(isset($model_act['model']) && isset($model_act['act']) ){
            $this->ajax = route('ajax', $model_act);
        }

        $query = request()->query();
        if(isset($query['query'])) {
            $this->ajax .= '?query=' . $query['query'];
        }

        if(isset($config['cols_display'])){
            $this->cols_display = $config['cols_display'];
        }
        if (isset($config['no_orderable_cols'])) {
            $this->no_orderable_cols = $config['no_orderable_cols'];
        }
        if(isset($this->cols_display['buttons'])){
            array_push($this->no_orderable_cols, 'buttons');
        }
        $this->head($varName);
        return $this->js;
    }
    private function head($varName){
        $this->js="var $varName = '';\n";
        $this->js.="$(function() {\n";
        $this->js.="\t$varName = $('#".$this->id."').DataTable({\n";

        if(app()->getLocale()=='tw') {
            $this->js .= "\t\t" . '"language": {' . "\n" .
                "\t\t\t" . '"url": "/js/datatable.tw.json"' . "\n" .
                "\t\t" . '},' . "\n";
        }
        $this->js.="\t\t".'"serverSide": true,'."\n";
        $this->js.="\t\t".'"stateSave": true,'."\n";
        $this->js.="\t\t".'"deferRender": true,'."\n";
        $this->js.="\t\t".'"stateDuration": 60 * 60,'."\n";
        $this->js.="\t\t".'"iDisplayLength": 50,'."\n";
        $this->js.="\t\t".'"pagingType": "simple_numbers",'."\n";
        //$this->js.="\t\t".'"order": [0, "desc"],'."\n";
        $this->js.="\t\t".'"ajax": {'."\n";
        $this->js.="\t\t".'url: "'.$this->ajax.'",'."\n";
        //$this->js.="\t\t".'url: "/new/api/hawk.json",'."\n";
        $this->js.="\t\t".'type:"get",'."\n";
        $this->js.="\t\t".'"dataSrc": "data",'."\n";
        $this->js.="\t\t".'"error": function (xhr, error, thrown) {}'."\n";
        $this->js.="\t\t".'},'."\n";

        $this->js.="\t\t".'"columns":['."\n";

        $this->cols();

        $this->js.="\t\t"."]\n";
        $this->js.="\t\t"."});";
        $this->js.="\t"."});";
    }
    private function cols(){
        if(count($this->cols_display)){
            $ccs = [];
            foreach($this->cols_display as $col => $cols){
                $cc="\t\t".'{'."\n";
                $bbbs = [];
                $bbbs[] ="\t\t\t". 'data: "'.$col.'"'."\n";
                if (isset($this->no_orderable_cols) && in_array($col, $this->no_orderable_cols)) {
                    $bbbs[] = "\t\t\t". "'orderable': false" . "\n";
                }
                if(isset($cols['cols_css'])) {
                    $css = $cols['cols_css'];
                    if(is_array($cols['cols_css'])){
                        $css = join(' ', $cols['cols_css']);
                    }
                    $bbbs[] ="\t\t\t". 'className: "' . $css . '"' . "\n";
                }

                if(isset($cols['render']) && count($cols['render'])){
                    if(!isset($cols['render']['url']) && !isset($cols['render']['boolean']) ){
                        $bbbs[] = $this->render->string($cols['render']['content']);
                    }else {
                        if (isset($cols['render']['url'])) {
                            $bbbs[] = $this->render->url($cols['render']);
                        }
                        if (isset($cols['render']['boolean'])) {
                            $bbbs[] = $this->render->boolean($cols['render']);
                        }
                    }
                }else{
                    $bbbs[] = $this->render->string('${row.'.$col.'}');
                }

                $cc.=join("\t\t".','."\n", $bbbs);


                $cc.="\t\t".'}'."\n";
                $ccs[] = $cc;
            }
            $this->js.=join(',', $ccs);
        }
    }
}