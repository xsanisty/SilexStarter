<?php

namespace SilexStarter\StaticProxy;

use XStatic\StaticProxy;

class Asset extends StaticProxy
{
    public static function getInstanceIdentifier()
    {
        return 'asset_manager';
    }
}
