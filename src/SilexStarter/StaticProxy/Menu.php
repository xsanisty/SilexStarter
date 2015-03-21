<?php

namespace SilexStarter\StaticProxy;

use Illuminate\Support\Facades\Facade as StaticProxy;

class Menu extends StaticProxy{

    protected static function getFacadeAccessor(){
        return 'menu_manager';
    }
}