<?php

namespace SilexStarter\StaticProxy;

use XStatic\StaticProxy;

class Database extends StaticProxy
{
    public static function getInstanceIdentifier()
    {
        return 'db';
    }
}
