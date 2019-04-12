<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 2019-04-11
 * Time: 11:17
 */

namespace Zhyu\Report;

use Illuminate\Support\Collection;

class ReportFactory
{
    public static function bind(string $name=null)
    {
        $lut = config('reports');
        if(is_null($lut)){
            throw new \Exception('Please create config/reports.php');
        }
        $className = Collection::make($lut)->get($name, 'abc');
        if($className=='abc'){
            throw new \Exception('Please create mapping class in config/reports.php');
        }
        app()->bind(ReportInterface::class, $className);
    }
}