<?php

namespace SilexStarter\Asset;

use Silex\Application;
use Silex\ServiceProviderInterface;

class AssetManagerServiceProvider implements ServiceProviderInterface{

    public function register(Application $app){
        $app['asset_manager'] = $app->share(function(){
            return new AssetManager;
        });
    }

    public function boot(Application $app){

    }
}