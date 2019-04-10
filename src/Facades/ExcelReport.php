<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 2019-03-26
 * Time: 13:29
 */

namespace Zhyu\Facades;


use Illuminate\Support\Facades\Facade;

class ExcelReport extends Facade
{
    protected static function getFacadeAccessor() { return 'excel.report'; }

}