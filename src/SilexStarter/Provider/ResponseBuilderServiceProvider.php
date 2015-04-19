<?php

namespace SilexStarter\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use SilexStarter\Response\ResponseBuilder;

class ResponseBuilderServiceProvider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app['response_builder'] = $app->share(function ($app) {
            return new ResponseBuilder($app['twig']);
        });
    }

    public function boot(Application $app)
    {
    }
}
