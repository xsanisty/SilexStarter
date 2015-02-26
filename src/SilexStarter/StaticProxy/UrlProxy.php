<?php

namespace SilexStarter\StaticProxy;

use Illuminate\Support\Facades\Facade as StaticProxy;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

class UrlProxy extends StaticProxy{

    protected static function getFacadeAccessor(){
        return 'url_generator';
    }

    public static function to($route){
        try{
            return static::$app['url_generator']->generate($route);
        }catch(RouteNotFoundException $e){
            return static::path($route);
        }
    }

    public static function path($path = '/'){
        $request = static::$app['request'];
        return $request->getScheme() . '://' . $request->getHost() . '/' . ltrim($path, '/');
    }

}