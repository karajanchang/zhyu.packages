<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 2019-03-01
 * Time: 22:34
 */

namespace Zhyu\Repositories\Eloquents;

use Illuminate\Support\Collection;
use Zhyu\Repositories\Contracts\RepositoryInterface;

class RepositoryApp{
    public static function bind($name){
        $class = config('repository.'.$name);
        if(strlen($class)==0){
            throw new \Exception("this config $name dos't exists!");
        }
        app()->bind(RepositoryInterface::class, function($app) use($class){
            return new $class($app, new Collection());
        });
    }
}