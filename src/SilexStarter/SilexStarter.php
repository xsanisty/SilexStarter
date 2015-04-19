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
    protected $app;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Register all services provider to the application container.
     *
     * @param array $providerList [List of service providers]
     *
     * @return [void]
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
     * @param [string] $controllerDir [The directory where controllers is located]
     *
     * @return [void]
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
     * Registering the application to Facade for StaticProxy access.
     *
     * @return [type] [description]
     */
    public function registerStaticProxy()
    {
        StaticProxy::setFacadeApplication($this);
    }

    /**
     * [registerAliases description].
     *
     * @param array $classes [description]
     *
     * @return [type] [description]
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
     * @param [string] $controller [the controller class name]
     *
     * @return [Closure] [description]
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

    public function boot()
    {
        if ($this['enable_module']) {
            $this['module']->boot();
        }

        parent::boot();
    }
}
