<?php

namespace SilexStarter\Contracts;

interface ModuleInstallerInterface extends ModuleInterface
{
    /**
     * Installing the module, covering:
     *     - publishing assets file
     *     - creating the database table
     *     - etc.
     */
    public function install();

    /**
     * Uninstalling the module, covering:
     *     - dropping table
     *     - removing asset file
     *     - etc.
     */
    public function uninstall();

    public function upgrade();

    public function downgrade();
}
