<?php

namespace SilexStarter\StaticProxies;

use Illuminate\Support\Facades\Facade as StaticProxy;

class SessionProxy extends StaticProxy{

    protected static function getFacadeAccessor(){
        return 'session';
    }
}