<?php

namespace SilexStarter\Module;

class ModuleResourceTest extends \PHPUnit_Framework_TestCase
{

    protected $resources = [
        'routes'        => 'Resources/routes.php',
        'middlewares'   => 'Resources/middlewares.php',
        'config'        => 'Resources/config',
        'views'         => 'Resources/views',
        'assets'        => 'Resources/assets',
        'controllers'   => 'Controller',
        'services'      => ['SomeServiceProvider'],
        'migration'     => 'Migration',
    ];

    protected $moduleResource;

    public function setUp()
    {
        $this->moduleResource = new ModuleResource($this->resources);
    }

    public function tearDown()
    {
        $this->moduleResource = null;
    }

    public function test_get_route_file()
    {
        assertSame($this->resources['routes'], $this->moduleResource->__get('routes'));
        assertSame($this->resources['routes'], $this->moduleResource->routes);
    }

    public function test_get_middleware_file()
    {
        assertSame($this->resources['middlewares'], $this->moduleResource->__get('middlewares'));
        assertSame($this->resources['middlewares'], $this->moduleResource->middlewares);
    }

    public function test_get_config_dir()
    {
        assertSame($this->resources['config'], $this->moduleResource->__get('config'));
        assertSame($this->resources['config'], $this->moduleResource->config);
    }

    public function test_get_views_dir()
    {
        assertSame($this->resources['views'], $this->moduleResource->__get('views'));
        assertSame($this->resources['views'], $this->moduleResource->views);
    }

    public function test_get_controllers_dir()
    {
        assertSame($this->resources['controllers'], $this->moduleResource->__get('controllers'));
        assertSame($this->resources['controllers'], $this->moduleResource->controllers);
    }

    public function test_get_service_list()
    {
        assertSame($this->resources['services'], $this->moduleResource->__get('services'));
        assertSame($this->resources['services'], $this->moduleResource->services);
    }

    public function test_get_migration_dir()
    {
        assertSame($this->resources['migration'], $this->moduleResource->__get('migration'));
        assertSame($this->resources['migration'], $this->moduleResource->migration);
    }
}
