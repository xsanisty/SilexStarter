<?php

return [
    'common' => [
        'Silex\Provider\SessionServiceProvider',
        'Silex\Provider\ServiceControllerServiceProvider',
        'Silex\Provider\UrlGeneratorServiceProvider',
        'SilexStarter\Provider\ModuleServiceProvider',
        'SilexStarter\Provider\TwigServiceProvider',
        'SilexStarter\Provider\EloquentServiceProvider',
        'SilexStarter\Provider\SentryServiceProvider',
        'SilexStarter\Provider\FilesystemServiceProvider',
        'SilexStarter\Provider\AssetManagerServiceProvider',
        'SilexStarter\Provider\MenuManagerServiceProvider',
    ],

    'development' => [
        'SilexStarter\Provider\WebProfilerServiceProvider' => ['profiler.cache_dir' => APP_PATH.'storage/profiler'],
    ],

    'console'   => [
        'SilexStarter\Provider\ConsoleServiceProvider'
    ]
];