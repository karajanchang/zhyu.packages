<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 2019-03-02
 * Time: 23:10
 */

namespace Zhyu\Datatables;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Collection;
use Zhyu\Datatables\Units\TestDatatables;


class DatatablesFactory {
    public static function bind(string $name = null){
        $lut = config('datatables');
        if(is_null($lut)){
            throw new \Exception('Please create config/datatables.php');
        }
        $className = Collection::make($lut)->get($name, TestDatatables::class);
        App::bind(DatatablesInterface::class, $className);
    }
}