<?php

namespace Admin;

use Silex\Application;
use SilexStarter\Module\ModuleResource;
use SilexStarter\Contracts\ModuleProviderInterface;

class ModuleProvider implements ModuleProviderInterface{

    protected $app;

    public function __construct(Application $app){
        $this->app = $app;
    }

    public function getModuleName(){
        return 'Xsanisty Admin Module';
    }

    public function getModuleAccessor(){
        return 'xsanisty-admin';
    }

    public function getRequiredModules(){
        return [];
    }

    public function getResources(){
        return new ModuleResource([
            'routes'        => 'Resources/routes.php',
            'middlewares'   => 'Resources/middlewares.php',
            'views'         => 'Resources/views',
            'controllers'   => 'Controller',
            'config'        => 'Resources/config'
        ]);
    }

    public function register(){
        $this->app->registerServices(
            $this->app['config']['xsanisty-admin::services']
        );
    }

    public function boot(){

    }
}