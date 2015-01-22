<?php

namespace SilexStarter\Module;

use ArrayAccess;
use Silex\Application;
use SilexStarter\Contracts\ModuleProviderInterface;

class ModuleManager{

    protected $app;
    protected $modules;
    protected $routes;
    protected $middlewares;

    public function __construct(Application $app){
        $this->app          = $app;
        $this->routes       = [];
        $this->middlewares  = [];
        $this->modules      = [];
    }

    public function isRegistered($module){
        return isset($this->modules[$module]);
    }

    /**
     * [registerModules description]
     * @param  array  $modules [description]
     * @return [type]          [description]
     */
    public function registerModules(array $modules){
        foreach ($modules as $moduleProvider) {
            $this->register(new $moduleProvider($this->app));
        }
    }

    /**
     * [registerModule description]
     * @param  ModuleProviderInterface $module [description]
     * @return [type]                          [description]
     */
    public function register(ModuleProviderInterface $module){
        $moduleManager = $this->app['module'];

        /** Check for required module, if not satisfied, throw exception immediately */
        foreach ($module->getRequiredModules() as $requiredModule) {
            if(!$moduleManager->isRegistered($requiredModule)){
                throw new \Exception($module->getModuleAccessor().' module require '.$requiredModule.' as its dependency', 1);
            }
        }

        /** Get the module path via the class reflection */
        $moduleReflection = new \ReflectionClass($module);
        $modulePath       = dirname($moduleReflection->getFileName());

        /** If controller_as_service enabled, register the controllers as service */
        if($this->app['controller_as_service']){
            $this->app->registerControllerDirectory(
                $modulePath.DIRECTORY_SEPARATOR.$module->getControllerDirectory(),
                $moduleReflection->getNamespaceName().'\\'.$module->getControllerDirectory()
            );
        }

        /** if route file exists, queue for later include */
        if($module->getRouteFile()){
            $moduleManager->addRouteFile($modulePath.'/'.$module->getRouteFile());
        }

        /** if middleware file exists, queue for later include */
        if($module->getMiddlewareFile()){
            $moduleManager->addMiddlewareFile($modulePath.'/'.$module->getMiddlewareFile());
        }

        /** if template file exists, register new template path under new namespace */
        if($module->getTemplateDirectory()){
            $this->app['twig.loader.filesystem']->addPath(
                $modulePath.'/'.$module->getTemplateDirectory(),
                $module->getModuleAccessor()
            );
        }

        $this->modules[$module->getModuleAccessor()] = $module;
        $module->register();
    }

    /**
     * [addRouteFile description]
     * @param [type] $path [description]
     */
    public function addRouteFile($path){
        if(!in_array($path, $this->routes)){
            $this->routes[] = $path;
        }
    }

    /**
     * [getRouteFiles description]
     * @return [type] [description]
     */
    public function getRouteFiles(){
        return $this->routes;
    }

    /**
     * [addMiddlewareFile description]
     * @param [type] $path [description]
     */
    public function addMiddlewareFile($path){
        if(!in_array($path, $this->middlewares)){
            $this->middlewares[] = $path;
        }
    }

    /**
     * [getMiddlewareFiles description]
     * @return [type] [description]
     */
    public function getMiddlewareFiles(){
        return $this->middlewares;
    }

    /*

    public function offsetGet($offset){

    }

    public function offsetSet($offset, $value){

    }

    public function offsetUnset($offset){

    }

    public function offsetExists($offset){

    }
    */

}