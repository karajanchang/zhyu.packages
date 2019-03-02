<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 2019-03-02
 * Time: 23:30
 */

namespace Zhyu\Datatables;

use Illuminate\Support\Facades\App;


class DatatablesFactoryApp
{
    public static function bind($name){
        DatatablesFactory::bind($name);
        $datatablesService = App::make(DatatablesService::class);
        return $datatablesService;
    }
}