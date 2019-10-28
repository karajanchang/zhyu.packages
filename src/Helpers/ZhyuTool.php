<?php


namespace Zhyu\Helpers;


use Zhyu\Tools\PlusMinusConvert;

class ZhyuTool
{
    public function plusMinusConvert(int $number){

        return app(PlusMinusConvert::class)->run($number);
    }

}