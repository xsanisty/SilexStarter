<?php

/**
 * This is documentation module also works as sample module to show you how develop module directly in application
 */
namespace Documentation;

use Silex\Application;
use SilexStarter\Module\ModuleInfo;
use SilexStarter\Module\ModuleResource;
use SilexStarter\Contracts\ModuleProviderInterface;

class ModuleProvider implements ModuleProviderInterface
{
    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function getInfo()
    {
        return new ModuleInfo(
            [
                'name'          => 'SilexStarter Documentation',
                'author_name'   => 'Xsanisty Development Team',
                'author_email'  => 'developers@xsanisty.com',
                'repository'    => 'https://github.com/xsanisty/SilexStarter-admin',
            ]
        );
    }

    public function getModuleIdentifier()
    {
        return 'silexstarter-doc';
    }

    public function getRequiredModules()
    {
        return ['xsanisty-dashboard'];
    }

    public function getResources()
    {
        return new ModuleResource(
            [
                'routes'        => 'Resources/routes.php',
                'views'         => 'Resources/views',
                'controllers'   => 'Controller',
            ]
        );
    }

    public function register()
    {
    }

    public function boot()
    {
    }
}
