<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 2019-03-26
 * Time: 13:29
 */

namespace Zhyu\Facades;


use Illuminate\Support\Facades\Facade;

class CsvReport extends Facade
{
    protected static function getFacadeAccessor() { return 'csv.report'; }

}