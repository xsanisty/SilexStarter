<?php

namespace SilexStarter;

use Exception;
use ReflectionClass;
use Silex\Application;
use FilesystemIterator;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;
/* to satisfy whom who always complain about laravel's facade */
use Illuminate\Support\Facades\Facade as StaticProxy;

class SilexStarter extends Application
{

    public function __construct()
    {
        parent::__construct();
        $this['app'] = $this;
    }

    /**
     * Register all services provider to the application container.
     *
     * @param array $providerList List of service providers
     */
    public function registerServices(array $providerList)
    {
        foreach ($providerList as $provider => $providerOptions) {
            if (is_numeric($provider)) {
                $this->register(new $providerOptions());
            } else {
                $this->register(new $provider(), $providerOptions);
            }
        }
    }

    /**
     * Search for controllers in the controllers dir and register it as a service.
     *
     * @param string $controllerDir The directory where controllers is located
     */
    public function registerControllerDirectory($controllerDir, $namespace = '')
    {
        $fileList = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($controllerDir, FilesystemIterator::SKIP_DOTS)
        );

        $namespace = ($namespace) ? rtrim($namespace, '\\').'\\' : '';

        foreach ($fileList as $file) {
            if ($file->getExtension() == 'php') {
                $controller = str_replace([$controllerDir, '.php', DIRECTORY_SEPARATOR], ['', '', '\\'], $file);
                $controller = ltrim($controller, '\\');

                $this[$namespace.$controller] = $this->share(
                    $this->controllerServiceClosureFactory($namespace.$controller)
                );
            }
        }
    }

    /**
     * Register class aliases.
     *
     * @param array $classes the list of alias => fully qualified class name
     */
    public function registerAliases(array $classes)
    {
        foreach ($classes as $alias => $class) {
            class_alias($class, $alias);
        }
    }

    /**
     * Provide controller service factory.
     *
     * @param string $controller Fully qualified controller class name
     *
     * @return Closure
     */
    protected function controllerServiceClosureFactory($controller)
    {
        return function ($app) use ($controller) {
            $controllerReflection   = new ReflectionClass($controller);
            $controllerConstructor  = $controllerReflection->getConstructor();

            /*
             * If constructor exists, build the dependency list from the dependency container
             */
            if ($controllerConstructor) {
                $constructorParameters  = $controllerConstructor->getParameters();
                $invocationParameters   = [];

                foreach ($constructorParameters as $parameterReflection) {
                    $parameterClassName = $parameterReflection->getClass()->getName();

                    switch ($parameterClassName) {
                        case 'Silex\Application':
                            $invocationParameters[] = $app;
                            break;
                        case 'Symfony\Component\HttpFoundation\Request':
                            $invocationParameters[] = $app['request'];
                            break;
                        default:
                            if ($app->offsetExists($parameterClassName)) {
                                $invocationParameters[] = $app[$parameterClassName];
                            } elseif (class_exists($parameterClassName)) {
                                $invocationParameters[] = new $parameterClassName();
                            } else {
                                throw new Exception("Can not resolve either $parameterClassName or it's instance from the container", 1);
                            }

                            break;
                    }
                }

                return $controllerReflection->newInstanceArgs($invocationParameters);

                /*
                 * Else, Instantiate the class directly
                 */
            } else {
                return $controllerReflection->newInstance();
            }
        };
    }

    /**
     * Register filter middleware to the ap container.
     *
     * @param string        $name     The name of the filter callback
     * @param \Closure|null $callback The closure callback to be registered
     *
     * @return \Closure|null
     */
    public function filter($name, \Closure $callback = null)
    {
        if (is_null($callback)) {
            return $this['filter.'.$name];
        }

        $this['filter.'.$name] = $this->protect($callback);
    }

    /**
     * Alias for filter method.
     */
    public function middleware($name, \Closure $callback = null)
    {
        return $this->filter($name, $callback);
    }

    /**
     * Bind an interface into specific service.
     *
     * @param string $interface the fully qualified interface/class name
     * @param string $service   the service key registered in container
     *
     * @return mixed the service object
     */
    public function bind($interface, $service)
    {
        $this[$interface] = $this->share(
            function () use ($service) {
                return $this[$service];
            }
        );
    }

    /**
     * Group route into specific pattern and apply same middleware.
     *
     * @param string  $pattern  Matched route pattern
     * @param Closure $callback The route callback
     * @param array   $options  The route options, including before and after middleware
     *
     * @return Controller
     */
    public function group($pattern, \Closure $callback, array $options = [])
    {
        return $this['route_builder']->group($pattern, $callback, $options);
    }

    /**
     * Group route into predefined resource pattern.
     *
     * @param string $pattern    Matched route pattern
     * @param string $controller The fully qualified controller class name
     * @param array  $options    The route options, including before and after middleware
     *
     * @return Controller
     */
    public function resource($pattern, $controller, array $options = [])
    {
        return $this['route_builder']->resource($pattern, $controller, $options);
    }

    /**
     * Build route based on available public method on the controller.
     *
     * @param string $pattern    Matched route pattern
     * @param string $controller The fully qualified controller class name
     * @param array  $options    The route options, including before and after middleware
     *
     * @return Controller
     */
    public function controller($pattern, $controller, array $options = [])
    {
        return $this['route_builder']->controller($pattern, $controller, $options);
    }

    /**
     * Maps a pattern to a callable.
     *
     * You can optionally specify HTTP methods that should be matched.
     *
     * @param string $pattern Matched route pattern
     * @param mixed  $to      Callback that returns the response when matched
     * @param array  $options The route options, including before and after middleware
     *
     * @return Controller
     */
    public function match($pattern, $to = null, array $options = [])
    {
        return $this['route_builder']->match($pattern, $to, $options);
    }

    /**
     * Maps a GET request to a callable.
     *
     * @param string $pattern Matched route pattern
     * @param mixed  $to      Callback that returns the response when matched
     * @param array  $options The route options, including before and after middleware
     *
     * @return Controller
     */
    public function get($pattern, $to = null, array $options = [])
    {
        return $this['route_builder']->get($pattern, $to, $options);
    }

    /**
     * Maps a POST request to a callable.
     *
     * @param string $pattern Matched route pattern
     * @param mixed  $to      Callback that returns the response when matched
     * @param array  $options The route options, including before and after middleware
     *
     * @return Controller
     */
    public function post($pattern, $to = null, array $options = [])
    {
        return $this['route_builder']->post($pattern, $to, $options);
    }

    /**
     * Maps a PUT request to a callable.
     *
     * @param string $pattern Matched route pattern
     * @param mixed  $to      Callback that returns the response when matched
     * @param array  $options The route options, including before and after middleware
     *
     * @return Controller
     */
    public function put($pattern, $to = null, array $options = [])
    {
        return $this['route_builder']->put($pattern, $to, $options);
    }

    /**
     * Maps a DELETE request to a callable.
     *
     * @param string $pattern Matched route pattern
     * @param mixed  $to      Callback that returns the response when matched
     * @param array  $options The route options, including before and after middleware
     *
     * @return Controller
     */
    public function delete($pattern, $to = null, array $options = [])
    {
        return $this['route_builder']->delete($pattern, $to, $options);
    }

    /**
     * Maps a PATCH request to a callable.
     *
     * @param string $pattern Matched route pattern
     * @param mixed  $to      Callback that returns the response when matched
     * @param array  $options The route options, including before and after middleware
     *
     * @return Controller
     */
    public function patch($pattern, $to = null, array $options = [])
    {
        return $this['route_builder']->patch($pattern, $to, $options);
    }

    /**
     * Boots all service providers.
     *
     * This method is automatically called by handle(), but you can use it
     * to boot all service providers and module when not handling a request.
     */
    public function boot()
    {
        if (!$this->booted) {
            foreach ($this->providers as $provider) {
                $provider->boot($this);
            }

            if ($this['enable_module']) {
                $this['module']->boot();
            }

            $this->booted = true;
        }
    }
}
