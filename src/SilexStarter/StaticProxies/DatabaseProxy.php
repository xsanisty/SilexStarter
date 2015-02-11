<?php

namespace SilexStarter\StaticProxies;

use Illuminate\Support\Facades\Facade as StaticProxy;

class DatabaseProxy extends StaticProxy{
    protected static function getFacadeAccessor(){
        return 'db';
    }
}