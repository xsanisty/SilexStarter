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

    public function test_get_info()
    {
        foreach ($this->resources as $key => $value) {
            assertSame($value, $this->moduleResource->__get($key));
            assertSame($value, $this->moduleResource->{$key});
        }
    }

    public function test_get_non_existence_resource()
    {
        assertNull($this->moduleResource->__get('oil'));
        assertNull($this->moduleResource->oil);
    }

    public function test_set_resources()
    {
        foreach ($this->resources as $key => $value) {
            $this->moduleResource->__set($key, 'another value');

            assertSame('another value', $this->moduleResource->__get($key));
        }

        foreach ($this->resources as $key => $value) {
            $this->moduleResource->{$key} = 'another value';

            assertSame('another value', $this->moduleResource->{$key});
        }
    }
}
