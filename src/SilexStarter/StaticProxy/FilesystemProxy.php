<?php

namespace SilexStarter\StaticProxy;

use Illuminate\Support\Facades\Facade as StaticProxy;

class FilesystemProxy extends StaticProxy{
    protected static function getFacadeAccessor(){
        return 'filesystem';
    }
}