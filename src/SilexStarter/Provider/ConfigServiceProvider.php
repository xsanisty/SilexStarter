<?php

namespace SilexStarter\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use SilexStarter\Config\ConfigurationContainer;

class ConfigServiceProvider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app['config'] = $app->share(function ($app) {
            return new ConfigurationContainer($app, $app['config.path']);
        });
    }

    public function boot(Application $app)
    {
    }
}
