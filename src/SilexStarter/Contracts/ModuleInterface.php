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
     * @param \Silex\Application $app [description]
     */
    public function __construct(\Silex\Application $app);

    /**
     * Get the module information.
     *
     * @return ModuleInfo The module information
     */
    public function getInfo();

    /**
     * Get module resources including route, config, views, etc.
     *
     * @return [type] [description]
     */
    public function getResources();

    /**
     * Get the name of the module that will be installed,
     * this will be  used to register template and config namespace as well
     * the name should be [a-zA-Z-_].
     *
     * @return string
     */
    public function getModuleAccessor();

    /**
     * Get the required module to be present.
     *
     * @return array List of module accessor name
     */
    public function getRequiredModules();
}
