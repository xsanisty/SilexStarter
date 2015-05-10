<?php

namespace SilexStarter\StaticProxy;

use XStatic\StaticProxy;

class Config extends StaticProxy
{
    public static function getInstanceIdentifier()
    {
        return 'config';
    }
}
