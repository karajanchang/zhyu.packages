<?php


namespace Zhyu\Tools;

//-----數值負轉正，正轉負
class PlusMinusConvert
{
    public function run($number = null){
        if(is_null($number)){

            return 0;
        }
        $number = (int) $number;

        return $number > 0 ? -1 * $number : abs($number);
    }

}