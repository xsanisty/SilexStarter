<?php

namespace SilexStarter\StaticProxy;

use Illuminate\Support\Facades\Facade as StaticProxy;

class UrlProxy extends StaticProxy{

    protected static function getFacadeAccessor(){
        return 'url_generator';
    }

    public static function to($name){
        return static::$app['url_generator']->generate($name);
    }

    public static function path($path = '/'){
        $request = static::$app['request'];
        return $request->getScheme() . '://' . $request->getHost() . '/' . ltrim($path, '/');
    }

}