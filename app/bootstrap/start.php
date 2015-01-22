<?php

/**
 * Bootstrapping Silex application, load the configuration, registering controllers,
 * including the routes and middlewares
 */

define('ROOT_PATH'  , __DIR__.'/../../');
define('VENDOR_PATH', __DIR__.'/../../vendor/');
define('APP_PATH'   , __DIR__.'/../../app/');
define('MODULE_PATH', __DIR__.'/../../app/modules/');
define('PUBLIC_PATH', __DIR__.'/../../public/');

require VENDOR_PATH.'autoload.php';

use SilexStarter\SilexStarter;
use SilexStarter\Config\ConfigurationServiceProvider;

/** Instantiate the extended Silex Application */
$app = new SilexStarter;

/** Load the configuration service provider and load base app configuration */
$app->register(new ConfigurationServiceProvider, ['config.path' => APP_PATH.'config']);
$app['config']->load('app');

/** Register the service provider listed in app/config/services.php */
$app->registerServices($app['config']['services']['common']);

if($app['environment'] == 'development'){
    $app->registerServices($app['config']['services']['development']);
}

/** temporary, until we move to new TwigServiceProvider */
$app['twig.loader.filesystem']->addPath(APP_PATH.'templates');

/** Load module provider if enabled */
if($app['enable_module']){
    $app['module']->registerModules($app['config']['modules']);
}

/** Register all controller as service if enabled */
if($app['controller_as_service']){
    $app->registerControllerDirectory(APP_PATH.'controllers');
}

/** Register Facade / Static Proxy if enabled */
if($app['enable_static_proxy']){
    $app->registerStaticProxy();
    $app->registerAliases($app['config']['aliases']);
}

/** Include the middlewares */
require APP_PATH.'middlewares.php';

if($app['enable_module']){
    foreach($app['module']->getMiddlewareFiles() as $middleware){
        require($middleware);
    }
}

/** Include the routes definition */
require APP_PATH.'routes.php';

if($app['enable_module']){
    foreach($app['module']->getRouteFiles() as $route){
        require($route);
    }
}

return $app;