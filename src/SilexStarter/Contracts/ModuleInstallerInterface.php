<?php

namespace SilexStarter\Contracts;

interface ModuleInstallerInterface extends ModuleInterface{

    /**
     * Installing the module, covering:
     *     - publishing assets file
     *     - creating the database table
     *     - etc
     * @return [type] [description]
     */
    public function install();

    /**
     * Uninstalling the module, covering:
     *     - dropping table
     *     - removing asset file
     *     - etc
     * @return [type] [description]
     */
    public function uninstall();

    /**
     * Get the assets directory, a directory relative to the ModuleInstaller class contain all asset
     * files including js, css, images etc
     * @return string
     */
    public function getAssetsDirectory();
}