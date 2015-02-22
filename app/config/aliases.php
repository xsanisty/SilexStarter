<?php

return [
    'DB'        => 'SilexStarter\StaticProxy\DatabaseProxy',
    'Url'       => 'SilexStarter\StaticProxy\UrlProxy',
    'App'       => 'SilexStarter\StaticProxy\ApplicationProxy',
    'Route'     => 'SilexStarter\StaticProxy\RouteProxy',
    'View'      => 'SilexStarter\StaticProxy\ViewProxy',
    'Response'  => 'SilexStarter\StaticProxy\ResponseProxy',
    'Request'   => 'SilexStarter\StaticProxy\RequestProxy',
    'Session'   => 'SilexStarter\StaticProxy\SessionProxy',
    'Config'    => 'SilexStarter\Config\ConfigurationProxy',
    'Module'    => 'SilexStarter\Module\ModuleManagerProxy',
    'Asset'     => 'SilexStarter\Asset\AssetManagerProxy',
    'Sentry'    => 'Cartalyst\Sentry\Facades\Laravel\Sentry',
    'Str'       => 'Illuminate\Support\Str',
];