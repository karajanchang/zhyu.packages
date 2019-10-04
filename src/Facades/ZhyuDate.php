<?php

namespace Zhyu\Facades;

use Illuminate\Support\Facades\Facade;

class ZhyuDate extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'ZhyuDate';
    }
}