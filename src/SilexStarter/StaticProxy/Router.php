<?php

namespace SilexStarter\StaticProxy;

use Illuminate\Support\Facades\Facade as StaticProxy;

class Router extends StaticProxy{

    protected static function getFacadeAccessor(){
        return 'route_builder';
    }

}