<?php

namespace SilexStarter\Router;

use Illuminate\Support\Str;
use Silex\Application;
use Silex\Controller;
use Silex\ControllerCollection;

class RouteBuilder
{
    /** controllers context stack */
    protected $contextStack = [];

    /** before handler stack */
    protected $beforeHandlerStack = [];

    /** after handler stack */
    protected $afterHandlerStack = [];

    /** Silex\Application instance */
    protected $app;

    /** Illuminate\Support\Str instance */
    protected $stringHelper;

    /**
     * Construct the RouteBuilder object.
     *
     * @param Application $app
     * @param Str         $str
     */
    public function __construct(Application $app, Str $str)
    {
        $this->app            = $app;
        $this->stringHelper   = $str;
    }

    /**
     * Push the ControllerCollection into context stack,
     * the lastest instance in context will be used by get, match, etc for route grouping.
     *
     * @param ControllerCollection $context
     */
    protected function pushContext(ControllerCollection $context)
    {
        $this->contextStack[] = $context;
    }

    /**
     * Retrieve the latest ControllerCollection and remove the instance from the context.
     */
    protected function popContext()
    {
        return array_pop($this->contextStack);
    }

    /**
     * Get the current context, the latest ControllerCollection in context stack
     * or root ControllerCollection instance if context stack is empty.
     *
     * @return Silex\ControllerCollection
     */
    protected function getContext()
    {
        if ($this->contextStack) {
            return end($this->contextStack);
        } else {
            return $this->app['controllers'];
        }
    }

    protected function pushBeforeHandler(\Closure $beforeHandler)
    {
        $this->beforeHandlerStack[] = $beforeHandler;
    }

    protected function popBeforeHandler()
    {
        return array_pop($this->beforeHandlerStack);
    }

    protected function getBeforeHandler()
    {
        return $this->beforeHandlerStack;
    }

    protected function pushAfterHandler(\Closure $afterHandler)
    {
        array_unshift($this->afterHandlerStack, $afterHandler);
    }

    protected function popAfterHandler()
    {
        return array_shift($this->afterHandlerStack);
    }

    protected function getAfterHandler()
    {
        return $this->afterHandlerStack;
    }

    protected function applyControllerOption(Controller $route, array $options)
    {
        foreach ($this->getBeforeHandler() as $before) {
            $route->before($before);
        }

        if (isset($options['before'])) {
            $route->before($options['before']);
        }

        if (isset($options['after'])) {
            $route->after($options['after']);
        }

        foreach ($this->getAfterHandler() as $after) {
            $route->after($after);
        }

        if (isset($options['as'])) {
            $route->bind($options['as']);
        }

        return $route;
    }

    public function match($pattern, $to = null, array $options = [])
    {
        $route = $this->getContext()->match($pattern, $to);
        $route = $this->applyControllerOption($route, $options);

        return $route;
    }

    public function get($pattern, $to = null, array $options = [])
    {
        $route = $this->getContext()->get($pattern, $to);
        $route = $this->applyControllerOption($route, $options);

        return $route;
    }

    public function post($pattern, $to = null, array $options = [])
    {
        $route = $this->getContext()->post($pattern, $to);
        $route = $this->applyControllerOption($route, $options);

        return $route;
    }

    public function put($pattern, $to = null, array $options = [])
    {
        $route = $this->getContext()->put($pattern, $to);
        $route = $this->applyControllerOption($route, $options);

        return $route;
    }

    public function delete($pattern, $to = null, array $options = [])
    {
        $route = $this->getContext()->delete($pattern, $to);
        $route = $this->applyControllerOption($route, $options);

        return $route;
    }

    public function patch($pattern, $to = null, array $options = [])
    {
        $route = $this->getContext()->patch($pattern, $to);
        $route = $this->applyControllerOption($route, $options);

        return $route;
    }

    /**
     * Grouping route into controller collection and mount to specific prefix.
     *
     * @param string  $prefix   the route prefix
     * @param Closure $callable the route collection handler
     *
     * @return Silex\ControllerCollection controller collection that already mounted to $prefix
     */
    public function group($prefix, \Closure $callable, array $options = [])
    {
        if (isset($options['before'])) {
            $this->pushBeforeHandler($options['before']);
        }

        if (isset($options['after'])) {
            $this->pushAfterHandler($options['after']);
        }

        /* push the context to be accessed to callable route */
        $this->pushContext($this->app['controllers_factory']);

        $callable();

        $routeCollection = $this->popContext();

        if (isset($options['before'])) {
            $this->popBeforeHandler();
        }

        if (isset($options['after'])) {
            $this->popAfterHandler();
        }

        $this->getContext()->mount($prefix, $routeCollection);

        return $routeCollection;
    }

    /**
     * Build route into resourceful controller.
     *
     * @param string $prefix     the route prefix
     * @param string $controller the controller class
     * @param  array $options    the route options
     *
     * @return Silex\ControllerCollection
     */
    public function resource($prefix, $controller, array $options = [])
    {
        $prefix             = '/'.ltrim($prefix, '/');
        $routeCollection    = $this->app['controllers_factory'];
        $routePrefixName    = $this->stringHelper->slug($prefix);

        $resourceRoutes     = [
            'get'           => [
                'pattern'       => '/',
                'method'        => 'get',
                'handler'       => "$controller:index",
            ],
            'get_paginate'  => [
                'pattern'       => '/page/{page}',
                'method'        => 'get',
                'handler'       => "$controller:index",
            ],
            'get_create'    => [
                'pattern'       => '/create',
                'method'        => 'get',
                'handler'       => "$controller:create",
            ],
            'get_edit'      => [
                'pattern'       => '/{id}/edit',
                'method'        => 'get',
                'handler'       => "$controller:edit",
            ],
            'get_show'      => [
                'pattern'       => '/{id}',
                'method'        => 'get',
                'handler'       => "$controller:show",
            ],
            'post'          => [
                'pattern'       => '/',
                'method'        => 'post',
                'handler'       => "$controller:store",
            ],
            'put'           => [
                'pattern'       => '/{id}',
                'method'        => 'put',
                'handler'       => "$controller:update",
            ],
            'delete'        => [
                'pattern'       => '/{id}',
                'method'        => 'delete',
                'handler'       => "$controller:destroy",
            ],
        ];

        foreach ($resourceRoutes as $routeName => $route) {
            $routeCollection->{$route['method']}($route['pattern'], $route['handler'])
                            ->bind($routePrefixName.'_'.$routeName);
        }

        $currentContext     = $this->getContext();
        $parentGetHandler   = $currentContext->get($prefix, $resourceRoutes['get']['handler']);
        $parentPostHandler  = $currentContext->post($prefix, $resourceRoutes['post']['handler']);

        /* apply the middleware stack */
        foreach ($this->getBeforeHandler() as $before) {
            $routeCollection->before($before);
            $parentGetHandler->before($before);
            $parentPostHandler->before($before);
        }

        if (isset($options['before'])) {
            $routeCollection->before($options['before']);
            $parentGetHandler->before($options['before']);
            $parentPostHandler->before($options['before']);
        }

        if (isset($options['after'])) {
            $routeCollection->after($options['after']);
            $parentGetHandler->after($options['after']);
            $parentPostHandler->after($options['after']);
        }

        foreach ($this->getAfterHandler() as $after) {
            $routeCollection->after($after);
            $parentGetHandler->after($after);
            $parentPostHandler->after($after);
        }

        $currentContext->mount($prefix, $routeCollection);

        return $routeCollection;
    }

    /**
     * Build route to all available public method in controller class.
     *
     * @param string $prefix     the route prefix
     * @param string $controller the controller class name
     * @param array  $options    the route options
     *
     * @return Silex\ControllerCollection
     */
    public function controller($prefix, $controller, array $options = [])
    {
        $prefix             = '/'.ltrim($prefix, '/');
        $class              = new \ReflectionClass($controller);
        $controllerMethods  = $class->getMethods(\ReflectionMethod::IS_PUBLIC);
        $routeCollection    = $this->app['controllers_factory'];
        $uppercase          = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $acceptedMethod     = ['get', 'post', 'put', 'delete', 'head', 'options'];

        foreach ($controllerMethods as $method) {
            $methodName = $method->name;

            if (substr($methodName, 0, 2) != '__') {
                $parameterCount = $method->getNumberOfParameters();

                /* search first method segment until uppercase found */
                $pos        = strcspn($methodName, $uppercase);

                /* the http method get, put, post, etc */
                $httpMethod = substr($methodName, 0, $pos);

                /* the url path, index => getIndex */
                if (in_array($httpMethod, $acceptedMethod)) {
                    $urlPath    = $this->stringHelper->snake(strpbrk($methodName, $uppercase));
                } else {
                    $urlPath    = $this->stringHelper->snake($methodName);
                    $httpMethod = 'match';
                }

                /*
                 * Build the route
                 */
                if ($urlPath == 'index') {
                    $indexRoute = $this->getContext()->{$httpMethod}($prefix, $controller.':'.$methodName);
                    $route = $routeCollection->{$httpMethod}('/', $controller.':'.$methodName);

                    $this->applyControllerOption($indexRoute, $options);
                } elseif ($parameterCount) {
                    $urlPattern = $urlPath;
                    $urlParams  = $method->getParameters();

                    foreach ($urlParams as $param) {
                        $urlPattern .= '/{'.$param->getName().'}';
                    }

                    $route = $routeCollection->{$httpMethod}($urlPattern, $controller.':'.$methodName);

                    foreach ($urlParams as $param) {
                        if ($param->isDefaultValueAvailable()) {
                            $route->value($param->getName(), $param->getDefaultValue());
                        }
                    }
                } else {
                    $route = $routeCollection->{$httpMethod}($urlPath, $controller.':'.$methodName);
                }

                $route->bind($prefix.'_'.$httpMethod.'_'.strtolower($urlPath));
            }
        }

        foreach ($this->getBeforeHandler() as $before) {
            $routeCollection->before($before);
        }

        if (isset($options['before'])) {
            $routeCollection->before($options['before']);
        }

        if (isset($options['after'])) {
            $routeCollection->after($options['after']);
        }

        foreach ($this->getAfterHandler() as $after) {
            $routeCollection->after($after);
        }

        $this->getContext()->mount($prefix, $routeCollection);

        return $routeCollection;
    }
}
