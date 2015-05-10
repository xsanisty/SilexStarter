<?php

namespace SilexStarter\StaticProxy;

use XStatic\StaticProxy;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

class Url extends StaticProxy
{
    public static function getInstanceIdentifier()
    {
        return 'url_generator';
    }

    public static function to($route)
    {
        try {
            return static::$container->get('url_generator')->generate($route);
        } catch (RouteNotFoundException $e) {
            return static::path($route);
        }
    }

    public static function path($path = '/')
    {
        $request = static::$container->get('request');

        return $request->getScheme().'://'.$request->getHost().'/'.ltrim($path, '/');
    }
}
