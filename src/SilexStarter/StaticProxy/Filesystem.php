<?php

namespace SilexStarter\StaticProxy;

use XStatic\StaticProxy;

class Filesystem extends StaticProxy
{
    public static function getInstanceIdentifier()
    {
        return 'filesystem';
    }
}
