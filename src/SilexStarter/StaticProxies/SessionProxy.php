<?php

namespace SilexStarter\StaticProxies;

use Illuminate\Support\Facades\Facade as StaticProxy;

class SessionProxy extends StaticProxy{

    protected static function getFacadeAccessor(){
        return 'session';
    }

    public static function flash($name, $message){
        static::$app['session']->getFlashBag()->add($name, $message);
    }

    public static function getFlash($name, $default){
        return static::$app['session']->getFlashBag()->get($name, $default);
    }
}