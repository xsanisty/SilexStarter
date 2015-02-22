<?php

namespace SilexStarter\StaticProxy;

use Illuminate\Support\Facades\Facade as StaticProxy;
use Illuminate\Support\Str;
use Silex\ControllerCollection;

class RouteProxy extends StaticProxy{

    /** controllers context stack */
    protected static $contextStack = [];

    protected static function getFacadeAccessor(){
        return 'controllers';
    }

    protected static function pushContext(ControllerCollection $context){
        static::$contextStack[] = $context;
    }

    protected static function popContext(){
        return array_pop(static::$contextStack);
    }

    protected static function getContext(){
        if(static::$contextStack){
            return end(static::$contextStack);
        }else{
            return static::$app['controllers'];
        }
    }

    public static function get($pattern, $to = null){
        return static::getContext()->get($pattern, $to);
    }

    /**
     * Grouping route into controller collection and mount to specific prefix
     * @param  [string]     $prefix             the route prefix
     * @param  [Closure]    $callable           the route collection handler
     * @return [Silex\ControllerCollection]     controller collection that already mounted to $prefix
     */
    public static function group($prefix, \Closure $callable){
        $prefix = '/'.ltrim($prefix, '/');

        /** push the context to be accessed to callable route */
        static::pushContext(static::$app['controllers_factory']);

        $callable();

        $routeCollection = static::popContext();
        $currentContext  = static::getContext();

        $currentContext->mount($prefix, $routeCollection);

        return $currentContext;
    }

    /**
     * [resource description]
     * @param  [type] $prefix     [description]
     * @param  [type] $controller [description]
     * @return [type]             [description]
     */
    public static function resource($prefix, $controller){
        $prefix             = '/'.ltrim($prefix, '/');
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

        static::$app->mount($prefix, $routeCollection);

        return $routeCollection;
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

        static::$app->mount($prefix, $routeCollection);

        return $routeCollection;
    }
}