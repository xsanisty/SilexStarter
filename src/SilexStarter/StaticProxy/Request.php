<?php

namespace SilexStarter\StaticProxy;

use Illuminate\Support\Facades\Facade as StaticProxy;

class Request extends StaticProxy
{
    protected static function getFacadeAccessor()
    {
        return 'request';
    }
}
