<?php

namespace SilexStarter\Contracts;

interface ModuleProviderInterface extends ModuleInterface{
    /**
     * get the module resources to be registered to the application
     * @return array
     * [
     *     'routes'     => 'the routes file',
     *     'middlewares'=> 'the middleware files',
     *     'controllers'=> 'the controllers directory',
     *     'views'      => 'the template directory',
     *     'services'   => 'the services file',
     *     'config'     => 'the config directory'
     * ]
     */
    public function getResources();

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