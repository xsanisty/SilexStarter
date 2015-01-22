<?php

namespace SilexStarter\Contracts;

interface ModuleProviderInterface extends ModuleInterface{
    /**
     * get the module routes file to be appended to the application route
     * @return string   the route file location, relative to the ModuleProvider class file
     */
    public function getRouteFile();

    /**
     * get the module middlewares file to be appended to the application middleware
     * @return string   the middleware file location, relative to the ModuleProvider class file
     */
    public function getMiddlewareFile();

    /**
     * get the module templates path, to be registered under new namespace based on moduleAccessor
     * @return string   the templates directory, relative to the ModuleProvider class file
     */
    public function getTemplateDirectory();

    /**
     * [getControllerDirectory description]
     * @return [type] [description]
     */
    public function getControllerDirectory();

    /**
     * register the module, module's service provider, or twig extension here
     * @return void
     */
    public function register();

    /**
     * setup the required action here
     * @return void
     */
    public function boot();
}