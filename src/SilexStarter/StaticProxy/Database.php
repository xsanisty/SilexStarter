<?php

namespace SilexStarter\StaticProxy;

use Illuminate\Support\Facades\Facade as StaticProxy;

class Database extends StaticProxy
{
    protected static function getFacadeAccessor()
    {
        return 'db';
    }
}
