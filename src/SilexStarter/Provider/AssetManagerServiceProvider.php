<?php

namespace SilexStarter\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use SilexStarter\Asset\AssetManager;

class AssetManagerServiceProvider implements ServiceProviderInterface{

    public function register(Application $app){
        $app['asset_manager'] = $app->share(function(Application $app){
            return new AssetManager(
                $app['enable_profiler'] ? Request::createFromGlobals() : $app['request'],
                'assets'
            );
        });
    }

    public function boot(Application $app){

    }
}