<?php


namespace Zhyu\Helpers;


use Illuminate\Support\Collection;
use Zhyu\Tools\Csv;
use Zhyu\Tools\Ip;
use Zhyu\Tools\PlusMinusConvert;
use Zhyu\Tools\Zip;

class ZhyuTool
{
    /**
     * Tool constructor.
     */
    public function __construct()
    {
        return $this;
    }

    public function csv($title, array $columns, Collection $collections){
        $app = app(Csv::class);

        return $app->output($title, $columns, $collections);
    }

    public function ip(){
        $app = app(Ip::class);

        return $app->get();
    }

    public function plusMinusConvert(int $number = 0){
        $app = app(PlusMinusConvert::class);

        return $app->run($number);
    }

    public function zip(){
        $app = app(Zip::class);

        return $app;
    }
}