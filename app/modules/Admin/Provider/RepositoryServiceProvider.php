<?php

namespace Admin\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Admin\Repository\UserRepository;

class RepositoryServiceProvider implements ServiceProviderInterface{

    public function register(Application $app){
        $app['Admin\Repository\UserRepository'] = $app->share(function(Application $app){
            return new UserRepository(new $app['config']['sentry.users.model']);
        });
    }

    public function boot(Application $app){

    }
}