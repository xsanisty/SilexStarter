<?php

namespace SilexStarter\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Illuminate\Support\Str;
use SilexStarter\Router\RouteBuilder;

class RouterServiceProvider implements ServiceProviderInterface{

    public function register(Application $app){
        $app['router'] = $app->share(function(Application $app){
            return new RouteBuilder($app, new Str);
        });
    }

    public function boot(Application $app){

    }
}