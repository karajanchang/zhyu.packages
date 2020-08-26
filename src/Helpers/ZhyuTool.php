<?php


namespace Zhyu\Helpers;


use Illuminate\Support\Collection;
use Zhyu\Tools\Csv;
use Zhyu\Tools\Ip;
use Zhyu\Tools\PlusMinusConvert;
use Zhyu\Tools\UrlMakeQuery;
use Zhyu\Tools\VersionCompare;
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

    public function urlMakeQuery(string $divide = null){
        if(!empty($divide)){
            $app = app(UrlMakeQuery::class, ['divide' => $divide]);
        }else{
            $app = app(UrlMakeQuery::class);
        }

        return $app;
    }

    public function zip(){
        $app = app(Zip::class);

        return $app;
    }

    public function versionOutOfDate($version, $version_ask){

        return app(VersionCompare::class, ['version' => $version, 'version_ask' => $version_ask])->isOutOfDate();
    }
}