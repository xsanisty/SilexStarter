<?php

return [
    'DB'        => 'SilexStarter\StaticProxy\DatabaseProxy',
    'Url'       => 'SilexStarter\StaticProxy\UrlProxy',
    'App'       => 'SilexStarter\StaticProxy\ApplicationProxy',
    'File'      => 'SilexStarter\StaticProxy\FilesystemProxy',
    'View'      => 'SilexStarter\StaticProxy\ViewProxy',
    'Route'     => 'SilexStarter\StaticProxy\RouteProxy',
    'Session'   => 'SilexStarter\StaticProxy\SessionProxy',
    'Request'   => 'SilexStarter\StaticProxy\RequestProxy',
    'Response'  => 'SilexStarter\StaticProxy\ResponseProxy',
    'Config'    => 'SilexStarter\Config\ConfigurationProxy',
    'Module'    => 'SilexStarter\Module\ModuleManagerProxy',
    'Asset'     => 'SilexStarter\Asset\AssetManagerProxy',
    'Sentry'    => 'Cartalyst\Sentry\Facades\Laravel\Sentry',
    'Str'       => 'Illuminate\Support\Str',
];