<?php

return [
    'common' => [
        'Silex\Provider\SessionServiceProvider',
        'Silex\Provider\ServiceControllerServiceProvider',
        'Silex\Provider\UrlGeneratorServiceProvider',
        'SilexStarter\Module\ModuleServiceProvider',
        'SilexStarter\Provider\TwigServiceProvider',
        'SilexStarter\Provider\EloquentServiceProvider',
        'SilexStarter\Provider\SentryServiceProvider',
        'SilexStarter\Asset\AssetManagerServiceProvider',
        'SilexStarter\Menu\MenuManagerServiceProvider',
    ],

    'development' => [
        'SilexStarter\Provider\WebProfilerServiceProvider' => ['profiler.cache_dir' => APP_PATH.'storage/profiler'],
    ],

    'console'   => [
        'SilexStarter\Provider\ConsoleServiceProvider'
    ]
];