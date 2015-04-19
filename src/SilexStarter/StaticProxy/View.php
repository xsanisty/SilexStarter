<?php

namespace SilexStarter\StaticProxy;

use Illuminate\Support\Facades\Facade as StaticProxy;

class View extends StaticProxy
{
    protected static function getFacadeAccessor()
    {
        return 'twig';
    }

    public static function make($template, $data = [])
    {
        return static::$app['twig']->render($template.'.twig', $data);
    }
}
