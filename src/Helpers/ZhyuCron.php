<?php


namespace Zhyu\Helpers;


class ZhyuCron
{
    public function string($date, $time){
        $dt = new \DateTime($date.' '.$time);
        $minute = intval($dt->format('i'));
        $hour = $dt->format('G');
        $day = $dt->format('j');
        $month = $dt->format('n');

        return "$minute $hour $day $month *";
    }

    public function wday($time, $wday){
        $dt = new \DateTime(date('Y-m-d '.$time));
        $minute = intval($dt->format('i'));
        $hour = $dt->format('G');

        return "$minute $hour * * $wday";
    }
}