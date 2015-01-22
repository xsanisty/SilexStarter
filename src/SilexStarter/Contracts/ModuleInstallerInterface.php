<?php

namespace SilexStarter\Contracts;

interface ModuleInstallerInterface extends ModuleInterface{

    /**
     * Installing the module, covering:
     *     - publishing configuration
     *     - publishing assets file
     *     - creating the database table
     *     - etc
     * @return [type] [description]
     */
    public function install();

    /**
     * Uninstalling the module, covering:
     *     - dropping table
     *     - removing configuration file
     *     - removing asset file
     *     - etc
     * @return [type] [description]
     */
    public function uninstall();

    /**
     * Get the module directory, a directory relative to the ModuleInstaller class file contain all
     * configuration files.
     * all file inside this directory will be copied to the app/config/{this.getModuleAccessor}
     * and can be accessed via Config::get('{this.getModuleAccessor}:config_file.some_key.some_sub_key')
     * @return string
     */
    public function getConfigDirectory();

    /**
     * Get the assets directory, a directory relative to the ModuleInstaller class contain all asset
     * files including js, css, images etc
     * @return string
     */
    public function getAssetsDirectory();
}