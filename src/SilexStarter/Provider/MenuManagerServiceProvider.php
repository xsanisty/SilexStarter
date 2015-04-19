<?php

namespace SilexStarter\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use SilexStarter\Menu\MenuManager;

class MenuManagerServiceProvider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app['menu_manager'] = $app->share(function () {
            return new MenuManager();
        });
    }

    public function boot(Application $app)
    {
    }
}
