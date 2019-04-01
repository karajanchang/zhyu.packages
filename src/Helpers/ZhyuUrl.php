<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 2019-03-26
 * Time: 13:26
 */

namespace Zhyu\Helpers;


class ZhyuUrl
{
    public function encode(... $params)
    {

        return join(':', $params);
    }
    public function decode($query){

        return explode(':', $query);
    }

    public function multiEncode(array $multi_params){
        $p = [];
        if(is_array($multi_params) && count($multi_params)) {
            foreach($multi_params as $params) {
                array_push($p, $this->encode($params));
            }
        }

        return join('*', $p);
    }

    public function multiDecode(array $multi_querys){
        $p = [];
        if(is_array($multi_querys) && count($multi_querys)) {
            foreach($multi_querys as $querys) {
                array_push($p, explode('*', $querys));
            }
        }

        return array_map(function($var){
            return explode(':', $var);
        }, $p);
    }
}