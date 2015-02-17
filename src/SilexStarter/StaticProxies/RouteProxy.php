<?php

namespace SilexStarter\StaticProxies;

use Illuminate\Support\Facades\Facade as StaticProxy;
use Illuminate\Support\Str;

class RouteProxy extends StaticProxy{
    protected static function getFacadeAccessor(){
        return 'controllers';
    }

    /**
     * Grouping route into controller collection and mount to specific prefix
     * @param  [string]     $prefix             the route prefix
     * @param  [Closure]    $callable           the route collection handler
     * @return [Silex\ControllerCollection]     controller collection that already mounted to $prefix
     */
    public static function group($prefix, \Closure $callable){
        $prefix = '/'.ltrim($prefix, '/');
        $controllerCollection = static::$app['controllers_factory'];

        $callable($controllerCollection);

        return static::$app->mount($prefix, $controllerCollection);
    }

    /**
     * [resource description]
     * @param  [type] $prefix     [description]
     * @param  [type] $controller [description]
     * @return [type]             [description]
     */
    public static function resource($prefix, $controller){
        $routeCollection    = static::$app['controllers_factory'];
        $routePrefixName    = Str::slug($prefix);

        $resourceRoutes     = array(
            'get'           => array(
                'pattern'       => '/',
                'method'        => 'get',
                'handler'       => "$controller:index"
            ),
            'get_paginate'  => array(
                'pattern'       => "/page/{page}",
                'method'        => 'get',
                'handler'       => "$controller:index"
            ),
            'get_create'    => array(
                'pattern'       => "/create",
                'method'        => 'get',
                'handler'       => "$controller:create"
            ),
            'get_edit'      => array(
                'pattern'       => "/{id}/edit",
                'method'        => 'get',
                'handler'       => "$controller:edit"
            ),
            'get_show'      => array(
                'pattern'       => "/{id}",
                'method'        => 'get',
                'handler'       => "$controller:show"
            ),
            'post'          => array(
                'pattern'       => '/',
                'method'        => 'post',
                'handler'       => "$controller:store"
            ),
            'put'           => array(
                'pattern'       => "/{id}",
                'method'        => 'put',
                'handler'       => "$controller:update"
            ),
            'delete'        => array(
                'pattern'       => "/{id}",
                'method'        => 'delete',
                'handler'       => "$controller:destroy"
            )
        );

        foreach ($resourceRoutes as $routeName => $route) {
            $routeCollection->{$route['method']}($route['pattern'], $route['handler'])
                            ->bind($routePrefixName.'_'.$routeName);
        }

        return static::$app->mount($prefix, $routeCollection);
    }

    /**
     * [controller description]
     * @param  [type] $prefix     [description]
     * @param  [type] $controller [description]
     * @return [type]             [description]
     */
    public static function controller($prefix, $controller){

        $class              = new \ReflectionClass($controller);
        $controllerMethods  = $class->getMethods(\ReflectionMethod::IS_PUBLIC);
        $routeCollection    = static::$app['controllers_factory'];
        $uppercase          = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

        foreach ($controllerMethods as $method) {
            $methodName = $method->name;

            if(substr($methodName, 0, 2) != '__'){
                $parameterCount = $method->getNumberOfParameters();

                $pos        = strcspn($methodName, $uppercase);

                /** the http method get, put, post, etc */
                $httpMethod = substr($methodName, 0, $pos);

                /** the url path, index => getIndex */
                $urlPath    = Str::snake(strpbrk($methodName, $uppercase));

                /**
                 * Build the route
                 */
                if($urlPath == 'index'){
                    $route = $routeCollection->{$httpMethod}('/', $controller.':'.$methodName);
                }else if($parameterCount){
                    $urlPattern = $urlPath;
                    $urlParams  = $method->getParameters();

                    foreach ($urlParams as $param) {
                        $urlPattern .= '/{'.$param->getName().'}';
                    }

                    $route = $routeCollection->{$httpMethod}($urlPattern, $controller.':'.$methodName);

                    foreach ($urlParams as $param) {
                        if($param->isDefaultValueAvailable()){
                            $route->value($param->getName(), $param->getDefaultValue());
                        }
                    }
                }else{
                    $route = $routeCollection->{$httpMethod}($urlPath, $controller.':'.$methodName);
                }

                $route->bind($prefix.'_'.$httpMethod.'_'.strtolower($urlPath));
            }
        }

        return static::$app->mount($prefix, $routeCollection);
    }
}