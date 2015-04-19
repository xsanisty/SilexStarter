<?php

/**
 * Bootstrapping Silex application, load the configuration, registering controllers,
 * including the routes and middlewares.
 */
define('ROOT_PATH', __DIR__.'/../../');
define('VENDOR_PATH', __DIR__.'/../../vendor/');
define('APP_PATH', __DIR__.'/../../app/');
define('MODULE_PATH', __DIR__.'/../../app/modules/');
define('PUBLIC_PATH', __DIR__.'/../../public/');

require VENDOR_PATH.'autoload.php';

use SilexStarter\SilexStarter;
use SilexStarter\Provider\ConfigServiceProvider;
use Symfony\Component\Debug\ErrorHandler;
use Symfony\Component\Debug\ExceptionHandler;

/* Instantiate the extended Silex Application */
$app = new SilexStarter();

/* Load the configuration service provider and load base app configuration */
$app->register(new ConfigServiceProvider(), ['config.path' => APP_PATH.'config']);
$app['config']->load('app');

/* register the error handler */
ErrorHandler::register($app['environment'] == 'development' && $app['debug']);
ExceptionHandler::register($app['environment'] == 'development' && $app['debug']);

/* Register the service provider listed in app/config/services.php */
$app->registerServices($app['config']['services.common']);

if ($app['environment'] == 'development') {
    $app->registerServices($app['config']['services.development']);
}

/* Load module provider if enabled */
if ($app['enable_module']) {
    $app['module']->registerModules($app['config']['modules']);
}

/* Register all controller as service if enabled */
if ($app['controller_as_service']) {
    $app->registerControllerDirectory(APP_PATH.'controllers');
}

/* Register Facade / Static Proxy if enabled */
if ($app['enable_static_proxy']) {
    $app->registerStaticProxy();
    $app->registerAliases($app['config']['aliases']);
}

/* Include the middlewares, load module middleware first to enable override */
if ($app['enable_module']) {
    foreach ($app['module']->getMiddlewareFiles() as $middleware) {
        require $middleware;
    }
}
require APP_PATH.'middlewares.php';

/* Include the routes definition, load module route first to enable override */
if ($app['enable_module']) {
    foreach ($app['module']->getRouteFiles() as $route) {
        require $route;
    }
}
require APP_PATH.'routes.php';

return $app;
