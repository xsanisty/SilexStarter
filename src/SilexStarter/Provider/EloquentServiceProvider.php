<?php

namespace SilexStarter\Provider;

use Silex\Application;
use Illuminate\Events\Dispatcher;
use Silex\ServiceProviderInterface;
use Illuminate\Database\Capsule\Manager as DatabaseManager;

class EloquentServiceProvider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app['db'] = $app->share(function ($app) {
            $databaseManager = new DatabaseManager();
            $eventDispatcher = new Dispatcher();

            $defaultConnection  = $app['config']['database']['default'];
            $connectionConfig   = $app['config']['database']['connections'];

            $databaseManager->addConnection($connectionConfig[$defaultConnection]);
            $databaseManager->setEventDispatcher($eventDispatcher);
            $databaseManager->setAsGlobal();

            return $databaseManager;
        });
    }

    public function boot(Application $app)
    {
        $app['db']->bootEloquent();
    }
}
