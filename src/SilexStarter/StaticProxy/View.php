<?php

namespace SilexStarter\StaticProxy;

use XStatic\StaticProxy;

class View extends StaticProxy
{
    public static function getInstanceIdentifier()
    {
        return 'twig';
    }

    public static function make($template, $data = [])
    {
        return static::$container->get('twig')->render($template.'.twig', $data);
    }
}
