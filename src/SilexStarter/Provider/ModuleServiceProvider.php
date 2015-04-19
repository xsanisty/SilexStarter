<?php

namespace SilexStarter\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use SilexStarter\Module\ModuleManager;

class ModuleServiceProvider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app['module'] = $app->share(function (Application $app) {
            return new ModuleManager($app);
        });
    }

    public function boot(Application $app)
    {
    }
}
