<?php

namespace SilexStarter\StaticProxy;

use XStatic\StaticProxy;

class Router extends StaticProxy
{
    public static function getInstanceIdentifier()
    {
        return 'route_builder';
    }
}
