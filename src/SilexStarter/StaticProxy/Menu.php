<?php

namespace SilexStarter\StaticProxy;

use XStatic\StaticProxy;

class Menu extends StaticProxy
{
    public static function getInstanceIdentifier()
    {
        return 'menu_manager';
    }
}
