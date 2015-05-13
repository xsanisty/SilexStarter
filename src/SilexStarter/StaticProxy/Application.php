<?php

namespace SilexStarter\StaticProxy;

use XStatic\StaticProxy;

class Application extends StaticProxy
{
    public static function getInstanceIdentifier()
    {
        return 'app';
    }

    public static function make($key)
    {
        return static::$container->get($key);
    }
}
