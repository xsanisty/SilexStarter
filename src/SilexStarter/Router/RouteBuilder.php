<?php

namespace SilexStarter\Router;

use Illuminate\Support\Str;
use Silex\Application;
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
     * @return ControllerCollection
     */
    protected function getContext()
    {
        if (!empty($this->contextStack)) {
            return end($this->contextStack);
        } else {
            return $this->app['controllers'];
        }
    }

    /**
     * Add new before handler to the end of middleware stack.
     *
     * @param array|string|Closure $beforeHandler The before middleware handler
     */
    protected function pushBeforeHandler($beforeHandler)
    {
        $this->beforeHandlerStack[] = $beforeHandler;
    }

    /**
     * Retreive latest middleware from the middleware stack.
     *
     * @return array|Closure Closure or array of closure
     */
    protected function popBeforeHandler()
    {
        return array_pop($this->beforeHandlerStack);
    }

    /**
     * Get the full middleware stack.
     *
     * @return array
     */
    protected function getBeforeHandler()
    {
        return $this->beforeHandlerStack;
    }

    /**
     * Add new after handler to the top of middleware stack.
     *
     * @param array|string|Closure $afterHandler The after middleware handler
     */
    protected function pushAfterHandler($afterHandler)
    {
        array_unshift($this->afterHandlerStack, $afterHandler);
    }

    /**
     * Retreive first middleware from the middleware stack.
     *
     * @return array|Closure Closure or array of closure
     */
    protected function popAfterHandler()
    {
        return array_shift($this->afterHandlerStack);
    }

    /**
     * Get the full middleware stack.
     *
     * @return array
     */
    protected function getAfterHandler()
    {
        return $this->afterHandlerStack;
    }

    /**
     * Apply the middleware and binding to the controller.
     *
     * @param Controller|ControllerCollection $route   The controller or controller collection
     * @param array                           $options the route options
     *
     * @return Controller|ControllerCollection
     */
    protected function applyControllerOption($route, array $options)
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
     * @param string   $prefix   the route prefix
     * @param \Closure $callable the route collection handler
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
     * @param array  $options    the route options
     *
     * @return Silex\ControllerCollection
     */
    public function resource($prefix, $controller, array $options = [])
    {
        $prefix     = '/'.ltrim($prefix, '/');
        $routeMaps  = [
            'get'           => new RouteMap('get', '/', "$controller:index"),
            'get_paginate'  => new RouteMap('get', '/page/{page}', "$controller:index"),
            'get_create'    => new RouteMap('get', '/create', "$controller:create"),
            'get_edit'      => new RouteMap('get', '/{id}/edit', "$controller:edit"),
            'get_show'      => new RouteMap('get', '/{id}', "$controller:show"),
            'post'          => new RouteMap('post', '/', "$controller:store"),
            'put'           => new RouteMap('put', '/{id}', "$controller:update"),
            'delete'        => new RouteMap('delete', '/{id}', "$controller:destroy"),
        ];

        $routeCollection    = $this->buildControllerRoute($this->app['controllers_factory'], $routeMaps);

        $this->applyControllerOption($routeCollection, $options);

        $this->getContext()->mount($prefix, $routeCollection);

        return $routeCollection;
    }

    /**
     * Build route to all available public method in controller class.
     *
     * @param string $prefix     the route prefix
     * @param string $controller the controller class name or object
     * @param array  $options    the route options
     *
     * @return Silex\ControllerCollection
     */
    public function controller($prefix, $controller, array $options = [])
    {
        $prefix             = '/'.ltrim($prefix, '/');
        $routeMaps          = $this->createControllerRouteMap($controller);

        $routeCollection    = $this->buildControllerRoute($this->app['controllers_factory'], $routeMaps);

        $this->applyControllerOption($routeCollection, $options);
        $this->getContext()->mount($prefix, $routeCollection);

        return $routeCollection;
    }

    /**
     * Create list of route map based on controller's public method.
     *
     * @param object|string $controller Fully qualified controller class name or class instance
     *
     * @return array array of SilexStarter\Router\RouteMap
     */
    protected function createControllerRouteMap($controller)
    {
        $class              = new \ReflectionClass($controller);
        $controllerActions  = $class->getMethods(\ReflectionMethod::IS_PUBLIC);

        $uppercase          = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $acceptedMethod     = ['get', 'post', 'put', 'delete', 'head', 'options', 'patch'];

        $routeMaps          = [];

        foreach ($controllerActions as $action) {

            /* skip if method is considered magic method */
            if (strpos($action->name, '__') === 0) {
                continue;
            }

            $routeOptions   = [];
            $routeAction    = $class->getName().':'.$action->name;

            /* the http method get, put, post, etc */
            $httpMethod     = substr($action->name, 0, strcspn($action->name, $uppercase));

            /* the url path, index => getIndex */
            $routeName      = (in_array($httpMethod, $acceptedMethod))
                            ? $this->stringHelper->snake(strpbrk($action->name, $uppercase))
                            : $this->stringHelper->snake($action->name);

            $defaultParams  = [];
            $routePattern   = ($routeName === 'index') ? '/' : $routeName;

            foreach ($action->getParameters() as $param) {
                $routePattern .= '/{'.$param->getName().'}';

                if ($param->isDefaultValueAvailable()) {
                    $defaultParams[$param->getName()] = $param->getDefaultValue();
                }
            }

            $routeOptions['default'] = $defaultParams;

            $routeMaps[$routeName]  = new RouteMap($httpMethod, $routePattern, $routeAction, $routeOptions);
        }

        return $routeMaps;
    }

    /**
     * Apply route maps into route collection.
     *
     * @param ControllerCollection $router    The VontrollerCollection instance
     * @param array                $routeMaps List of RouteMap object
     *
     * @return ControllerCollection
     */
    protected function buildControllerRoute(ControllerCollection $router, array $routeMaps)
    {
        foreach ($routeMaps as $map) {
            $options = $map->getOptions();
            $pattern = $map->getPattern();
            $method  = $map->getHttpMethod() ? $map->getHttpMethod() : 'match';
            $route   = $router->$method($pattern, $map->getAction());

            if (isset($options['default'])) {
                foreach ($options['default'] as $field => $value) {
                    $route->value($field, $value);
                }
            }
        }

        return $router;
    }
}
