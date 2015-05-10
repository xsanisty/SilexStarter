<?php

namespace SilexStarter\StaticProxy;

use XStatic\StaticProxy;

class Response extends StaticProxy
{
    public static function getInstanceIdentifier()
    {
        return 'response_builder';
    }
}
