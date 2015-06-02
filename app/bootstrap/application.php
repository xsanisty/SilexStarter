<?php

$isConsole = defined('CONSOLE');
if (!$isConsole) {
    require 'bootstrap.php';
}

use Xstatic\ProxyManager;
use SilexStarter\SilexStarter;
use SilexStarter\Provider\ConfigServiceProvider;
use Symfony\Component\Debug\ErrorHandler;
use Symfony\Component\Debug\ExceptionHandler;

/* Instantiate the extended Silex Application */
$app = new SilexStarter();

/* Load the configuration service provider and load base app configuration */
$app->register(new ConfigServiceProvider(), ['config.path' => APP_PATH . 'config']);
$app['config']->load('app');

if (!$isConsole) {
    ErrorHandler::register();
    ExceptionHandler::register($app['environment'] == 'development' && $app['debug']);
}

/* Register the service provider listed in app/config/services.php */
$app->registerServices($app['config']['services.common']);

if (!$isConsole) {
    $app->registerServices($app['config']['services.web']);
}

if (!$isConsole && $app['environment'] == 'development') {
    $app->registerServices($app['config']['services.web_dev']);
}

/* Load module provider if enabled */
if ($app['enable_module']) {
    $app['module']->registerModules($app['config']['modules']);
}

/* Register all controller as service if enabled */
if ($app['controller_as_service']) {
    $app->registerControllerDirectory(APP_PATH . '/controllers');
}

/* Register Static Proxy if enabled */
if ($app['enable_static_proxy']) {
    $app['static_proxy_manager']->enable(ProxyManager::ROOT_NAMESPACE_ANY);
    foreach ($app['config']['aliases'] as $alias => $concrete) {
        $app['static_proxy_manager']->addProxy($alias, $concrete);
    }
}

/* Include the middlewares, load module middleware first to enable override */
if ($app['enable_module']) {
    foreach ($app['module']->getMiddlewareFiles() as $middleware) {
        require $middleware;
    }
}
require APP_PATH . 'middlewares.php';

/* Include the routes definition, load module route first to enable override */
if ($app['enable_module']) {
    foreach ($app['module']->getRouteFiles() as $route) {
        require $route;
    }
}
require APP_PATH . 'routes.php';

return $app;
