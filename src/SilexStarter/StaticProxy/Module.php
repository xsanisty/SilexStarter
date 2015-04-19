<?php

namespace SilexStarter\StaticProxy;

use Illuminate\Support\Facades\Facade as StaticProxy;

class Module extends StaticProxy
{
    protected static function getFacadeAccessor()
    {
        return 'module';
    }
}
