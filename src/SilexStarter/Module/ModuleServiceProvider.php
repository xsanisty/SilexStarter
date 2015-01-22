<?php

namespace SilexStarter\Module;

use Silex\Application;
use Silex\ServiceProviderInterface;

class ModuleServiceProvider implements ServiceProviderInterface{

    public function register(Application $app){
        $app['module'] = $app->share(function(Application $app){
            return new ModuleManager($app);
        });
    }

    public function boot(Application $app){

    }
}