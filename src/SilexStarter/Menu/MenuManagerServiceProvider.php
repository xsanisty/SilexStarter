<?php

namespace SilexStarter\Menu;

use Silex\Application;
use Silex\ServiceProviderInterface;

class MenuManagerServiceProvider implements ServiceProviderInterface{

    public function register(Application $app){
        $app['menu_manager'] = $app->share(function(){
            return new MenuManager();
        });
    }

    public function boot(Application $app){

    }
}