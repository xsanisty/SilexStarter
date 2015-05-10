<?php

namespace SilexStarter\StaticProxy;

use XStatic\StaticProxy;

class Module extends StaticProxy
{
    public static function getInstanceIdentifier()
    {
        return 'module';
    }
}
