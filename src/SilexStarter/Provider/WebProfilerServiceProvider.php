<?php

namespace SilexStarter\Provider;

use Silex\Application;
use Silex\Provider\WebProfilerServiceProvider as SilexProfilerServiceProvider;

class WebProfilerServiceProvider extends SilexProfilerServiceProvider
{
    public function register(Application $app)
    {
        if ($app['enable_profiler']) {
            parent::register($app);
        }
    }

    public function boot(Application $app)
    {
        if ($app['enable_profiler']) {
            parent::boot($app);
        }
    }
}
