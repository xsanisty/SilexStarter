<?php

namespace SilexStarter\Contracts;

/**
 * Module provider and module installer should be placed at the root of the module directory.
 */
interface ModuleInterface
{
    /**
     * Module provider constructor.
     *
     * @param Silex\Application $app The Silex application instance
     */
    public function __construct(\Silex\Application $app);

    /**
     * Get the module information.
     *
     * @return SilexStarter\Module\ModuleInfo The module information
     */
    public function getInfo();

    /**
     * Get module resources including route, config, views, etc.
     *
     * @return SilexStarter\Module\ModuleResource
     */
    public function getResources();

    /**
     * Get the identifier of the module that will be installed,
     * this will be used to register template and config namespace as well
     * the name should be [a-zA-Z-_].
     *
     * @return string
     */
    public function getModuleIdentifier();

    /**
     * Get the required module to be present.
     *
     * @return array List of module accessor name
     */
    public function getRequiredModules();
}
