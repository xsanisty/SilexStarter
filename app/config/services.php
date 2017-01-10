<?php

return [
    'common' => [
        /** Set session.storage.handler option when you use custom session handler */
        'Silex\Provider\SessionServiceProvider' => ['session.storage.handler' => null],
        'Silex\Provider\ServiceControllerServiceProvider',
        'Silex\Provider\UrlGeneratorServiceProvider',
        'SilexStarter\Provider\ModuleServiceProvider',
        'SilexStarter\Provider\RouteBuilderServiceProvider',
        'SilexStarter\Provider\EloquentServiceProvider',
        'SilexStarter\Provider\SentryServiceProvider',
        'SilexStarter\Provider\FilesystemServiceProvider',
        'SilexStarter\Provider\AssetManagerServiceProvider',
        'SilexStarter\Provider\TwigServiceProvider',
        'SilexStarter\Provider\StaticProxyServiceProvider',
        'SilexStarter\Provider\MigrationServiceProvider',
        'SilexStarter\Provider\RequestHelperServiceProvider',
    ],

    'web' => [
        'Xsanisty\Admin\Provider\MenuManagerServiceProvider',
        'SilexStarter\Provider\ResponseBuilderServiceProvider',
    ],

    'web_dev' => [
        'SilexStarter\Provider\WebProfilerServiceProvider' => ['profiler.cache_dir' => APP_PATH.'storage/profiler'],
        'SilexStarter\Provider\WhoopsServiceProvider'
    ],

    'console'   => [
        'SilexStarter\Provider\ConsoleServiceProvider',
    ],

    'console_dev' => [

    ]
];
