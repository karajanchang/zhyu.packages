<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 2019-03-26
 * Time: 13:26
 */

namespace Zhyu\Helpers;


class ZhyuDate
{
    function validateDate($date, $format = 'Y-m-d')
    {
        $d = \DateTime::createFromFormat($format, $date);
        // The Y ( 4 digits year ) returns TRUE for any integer with any number of digits so changing the comparison from == to === fixes the issue.
        return $d && $d->format($format) === $date;
    }
    function make2word($input){
        return str_pad($input, 2, '0', STR_PAD_LEFT);
    }

    function getValidateDate($year, $month, $day, $hour, $minute){
        $date = $year.'-'.$this->make2word($month).'-'.$this->make2word($day);
        if($this->validateDate($date)===true){
            $second = $minute==59 ? '59' : '00';
            $dt = new \DateTime($date. ' '.$this->make2word($hour).':'.$this->make2word($minute).':'.$second);
            return $dt;
        }else{
            $day = $day - 1;
            return $this->getValidateDate($year, $month, $day, $hour, $minute);
        }
    }
}