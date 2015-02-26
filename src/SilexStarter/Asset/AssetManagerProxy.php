<?php

namespace SilexStarter\Asset;

use Illuminate\Support\Facades\Facade as StaticProxy;

class AssetManagerProxy extends StaticProxy{
    protected static function getFacadeAccessor(){
        return 'asset_manager';
    }
}