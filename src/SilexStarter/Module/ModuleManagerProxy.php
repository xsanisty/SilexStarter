<?php

namespace SilexStarter\Module;

use Illuminate\Support\Facades\Facade as StaticProxy;

class ModuleManagerProxy extends StaticProxy{
    protected static function getFacadeAccessor(){
        return 'module';
    }
}