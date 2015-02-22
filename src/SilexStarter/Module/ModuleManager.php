<?php

namespace SilexStarter\Module;

use ArrayAccess;
use Silex\Application;
use SilexStarter\Exception\ModuleRequiredException;
use SilexStarter\Contracts\ModuleProviderInterface;

class ModuleManager{

    protected $app;
    protected $modules      = [];
    protected $routes       = [];
    protected $middlewares  = [];
    protected $config;

    public function __construct(Application $app){
        $this->app          = $app;
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

        $moduleAccessor = $module->getModuleAccessor();

        /** Check for required module, if not satisfied, throw exception immediately */
        foreach ($module->getRequiredModules() as $requiredModule) {
            if(!$this->isRegistered($requiredModule)){
                throw new ModuleRequiredException($moduleAccessor . ' module require ' . $requiredModule . ' as its dependency');
            }
        }

        /** Get the module path via the class reflection */
        $moduleReflection = new \ReflectionClass($module);
        $modulePath       = dirname($moduleReflection->getFileName());
        $moduleResources  = $module->getResources();

        /** If controller_as_service enabled, register the controllers as service */
        if($this->app['controller_as_service'] && $moduleResources->controllers){
            $this->app->registerControllerDirectory(
                $modulePath . DIRECTORY_SEPARATOR . $moduleResources->controllers,
                $moduleReflection->getNamespaceName() . '\\' . $moduleResources->controllers
            );
        }

        /** if config dir exists, add namespace to the config reader */
        if($moduleResources->config){
            $this->app['config']->addDirectory(
                $modulePath . DIRECTORY_SEPARATOR . $moduleResources->config,
                $moduleAccessor
            );
        }

        /** if route file exists, queue for later include */
        if($moduleResources->routes){
            $this->addRouteFile($modulePath . '/' . $moduleResources->routes);
        }

        /** if middleware file exists, queue for later include */
        if($moduleResources->middlewares){
            $this->addMiddlewareFile($modulePath . '/' . $moduleResources->middlewares);
        }

        /** if template file exists, register new template path under new namespace */
        if($moduleResources->views){
            $this->app['twig.loader.filesystem']->addPath(
                $modulePath . '/' . $moduleResources->views,
                $moduleAccessor
            );
        }

        $this->modules[$moduleAccessor] = $module;
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