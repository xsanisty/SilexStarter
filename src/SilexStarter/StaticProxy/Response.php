<?php

namespace SilexStarter\StaticProxy;

use Illuminate\Support\Facades\Facade as StaticProxy;

class Response extends StaticProxy
{
    protected static function getFacadeAccessor()
    {
        return 'response_builder';
    }
}
