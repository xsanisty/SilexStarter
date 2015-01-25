<?php

namespace SilexStarter\StaticProxies;

use Illuminate\Support\Facades\Facade as StaticProxy;

class ViewProxy extends StaticProxy{
    protected static function getFacadeAccessor(){
        return 'twig';
    }

    public static function make($template, $data){
        return static::$app['twig']->render($template, $data);
    }
}