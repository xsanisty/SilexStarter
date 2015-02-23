<?php

namespace SilexStarter\Config;

use Illuminate\Support\Facades\Facade as StaticProxy;

class ConfigurationProxy extends StaticProxy{
    protected static function getFacadeAccessor(){
        return 'config';
    }
}