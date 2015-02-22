<?php

namespace SilexStarter\StaticProxy;

use Illuminate\Support\Facades\Facade as StaticProxy;

class SessionProxy extends StaticProxy{

    protected static function getFacadeAccessor(){
        return 'session';
    }

    public static function flash($name, $message){
        static::$app['session']->getFlashBag()->add($name, $message);
    }

    public static function getFlash($name, $default = null){
        $flash = static::$app['session']->getFlashBag()->get($name);

        return ($flash) ? $flash[0] : $default;
    }
}