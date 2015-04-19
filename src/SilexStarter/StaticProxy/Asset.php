<?php

namespace SilexStarter\StaticProxy;

use Illuminate\Support\Facades\Facade as StaticProxy;

class Asset extends StaticProxy
{
    protected static function getFacadeAccessor()
    {
        return 'asset_manager';
    }
}
