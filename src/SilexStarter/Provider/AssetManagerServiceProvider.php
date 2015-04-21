<?php

namespace SilexStarter\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use SilexStarter\Asset\AssetManager;

class AssetManagerServiceProvider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app['asset_manager'] = $app->share(function (Application $app) {
            return new AssetManager(
                $app['request_stack'],
                'assets'
            );
        });
    }

    public function boot(Application $app)
    {
    }
}
