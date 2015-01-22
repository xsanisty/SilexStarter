<?php

namespace Admin;

use Silex\Application;
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

    public function getRouteFile(){
        return 'Resources/routes.php';
    }

    public function getMiddlewareFile(){
        return 'Resources/middlewares.php';
    }

    public function getTemplateDirectory(){
        return 'Resources/views';
    }

    public function getControllerDirectory(){
        return 'Controllers';
    }

    public function register(){
        $this->app->registerServices(
            $this->app['config']['xsanisty-admin:services']
        );
    }

    public function boot(){

    }
}