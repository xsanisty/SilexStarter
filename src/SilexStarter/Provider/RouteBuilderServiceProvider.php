<?php

namespace SilexStarter\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Illuminate\Support\Str;
use SilexStarter\Router\RouteBuilder;

class RouteBuilderServiceProvider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app['route_builder'] = $app->share(function (Application $app) {
            return new RouteBuilder($app, new Str());
        });
    }

    public function boot(Application $app)
    {
    }
}
