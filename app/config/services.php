<?php

return [
    'common' => [
        'Silex\Provider\SessionServiceProvider',
        'Silex\Provider\ServiceControllerServiceProvider',
        'Silex\Provider\TwigServiceProvider',
        'Silex\Provider\UrlGeneratorServiceProvider',
        'SilexStarter\Module\ModuleServiceProvider',
        'SilexStarter\Providers\EloquentServiceProvider',
        'SilexStarter\Providers\SentryServiceProvider',
    ],

    'development' => [
        'Silex\Provider\WebProfilerServiceProvider' => ['profiler.cache_dir' => APP_PATH.'storage/profiler'],
    ]
];