<?php

namespace SilexStarter\StaticProxy;

use Illuminate\Support\Facades\Facade as StaticProxy;

class ApplicationProxy extends StaticProxy{
    protected static function getFacadeAccessor() {
        return self::$app;
    }

    public static function make($key)
    {
        return self::$app[$key];
    }
}