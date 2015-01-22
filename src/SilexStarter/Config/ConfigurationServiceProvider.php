<?php

namespace SilexStarter\Config;

use Silex\Application;
use Silex\ServiceProviderInterface;

class ConfigurationServiceProvider implements ServiceProviderInterface{

    public function register(Application $app){
        $app['config'] = $app->share(function($app){
            return new ConfigurationContainer($app, $app['config.path']);
        });
    }

    public function boot(Application $app){
    }
}