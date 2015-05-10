<?php

namespace SilexStarter\StaticProxy;

use XStatic\StaticProxy;

class Session extends StaticProxy
{
    public static function getInstanceIdentifier()
    {
        return 'session';
    }

    public static function flash($name, $message)
    {
        static::$container->get('session')->getFlashBag()->add($name, $message);
    }

    public static function getFlash($name, $default = null)
    {
        $flash = static::$container->get('session')->getFlashBag()->get($name);

        return ($flash) ? $flash[0] : $default;
    }
}
