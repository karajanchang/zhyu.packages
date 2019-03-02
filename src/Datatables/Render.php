<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 2018/5/7
 * Time: 上午11:27
 */

namespace Zhyu\Datatables;


class Render
{
    private function head(){
        $str="'render': function(data, type, row, meta){\n";
        return $str;
    }
    private function footer(){
        $str="\n}\n";
        return $str;
    }
    public function url($render){
        $str='';
        $str.=$this->head();

        if(!isset($render['url']['route']) || !isset($render['content'])){
            throw new \Exception('url route or content must have');
        }
        if(!is_array($render['url'])){
            throw new \Exception('url must been an array');
        }
        $route = $render['url']['route'];
        $params = $render['url']['params'];
        $content = $render['content'];


        $url = $this->parseUrlParams($route, $params, $content);
        //echo $url;exit;
        $str.="\t\t return ".'`<a href="'.$url.'">'.$render['content'].'</a>`;';

        $str.=$this->footer();
	    return $str;
    }
    private function getReplacePara($type, $key){
        switch(strtolower($type)){
            case 'string':
                $re = 'aaa-'.$key;
                break;
            default:
                $re = '111-'.$key;
        }
        return $re;
    }
    private function parseUrlParams($route, $params){
        $replaces = [];
        $displays = [];
        $pp = [];
        foreach($params as $key => $pa){
            $re = $this->getReplacePara($pa['type'], $key);
            $replaces[] = $re;
            $displays[] = $pa['display_col'];
            $pp[$pa['col']] = $re;
        }
        $url = route($route, $pp);
        if(count($replaces)){
           foreach($replaces as $key => $re){
           	    $rr = isset($params[$key]['val'])   ?   $params[$key]['val']    :   '${row.'.$displays[$key].'}';
                $url = str_replace($re, $rr, $url);
           }
        }
        return $url;

    }
    public function boolean($render){
        $str='';
        $str.=$this->head();
        if(count($render['boolean'])){
            $keys = array_keys($render['boolean']);
            if(is_int($keys[0])){
	            $str.= "let bb = data === " . $keys[0] . " ? '".$render['boolean'][$keys[0]]."' : '".$render['boolean'][$keys[1]]."';";
	            $str.='return `${bb}`;';
            }
	        if(is_string($keys[0])){
		        $str .= "let bb = data === '" . $keys[0] . "' ? '".$render['boolean'][$keys[0]]."' : '".$render['boolean'][$keys[1]]."';";
		        $str.='return `${bb}`;';
	        }
        }
        
        $str.=$this->footer();
        return $str;
    }


    public function string($string){
        $str='';
        $str.=$this->head();
        if(isset($string) && strlen($string)>0){
            $str.='return `'.$string.'`;';
        }
        $str.=$this->footer();
        return $str;
    }

}