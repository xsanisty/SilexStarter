<?php

namespace SilexStarter\Contracts;

interface ModuleProviderInterface extends ModuleInterface
{
    /**
     * get the module resources to be registered to the application.
     *
     * @return SilexStarter\Module\ModuleResource
     */
    public function getResources();

    /**
     * register the module, module's service provider, or twig extension here.
     */
    public function register();

    /**
     * setup the required action here.
     */
    public function boot();
}
