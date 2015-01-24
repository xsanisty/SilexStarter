<?php

return [
    'DB'        => 'SilexStarter\StaticProxies\DatabaseProxy',
    'App'       => 'SilexStarter\StaticProxies\ApplicationProxy',
    'Route'     => 'SilexStarter\StaticProxies\RouteProxy',
    'View'      => 'SilexStarter\StaticProxies\ViewProxy',
    'Response'  => 'SilexStarter\StaticProxies\ResponseProxy',
    'Request'   => 'SilexStarter\StaticProxies\RequestProxy',
    'Session'   => 'SilexStarter\StaticProxies\SessionProxy',
    'Config'    => 'SilexStarter\Config\ConfigurationProxy',
    'Module'    => 'SilexStarter\Module\ModuleManagerProxy',
    'Sentry'    => 'Cartalyst\Sentry\Facades\Laravel\Sentry',
];