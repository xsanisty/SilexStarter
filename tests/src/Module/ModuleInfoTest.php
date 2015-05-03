<?php

namespace SilexStarter\Module;

class ModuleInfoTest extends \PHPUnit_Framework_TestCase
{

    protected $info = [
        'author_name'   => 'Xsanisty',
        'author_email'  => 'developers@xsanisty.com',
        'repository'    => 'https://github.com/xsanisty/SilexStarter',
        'website'       => 'http://www.xsanisty.com',
        'name'          => 'SilexStarter',
        'description'   => 'Silex Starter App',
        'version'       => '1.0.0@beta'
    ];

    protected $moduleInfo;

    public function setUp()
    {
        $this->moduleInfo = new ModuleInfo($this->info);
    }

    public function tearDown()
    {
        $this->moduleInfo = null;
    }

    public function test_get_info()
    {
        foreach ($this->info as $key => $value) {
            assertSame($value, $this->moduleInfo->__get($key));
            assertSame($value, $this->moduleInfo->{$key});
        }
    }

    public function test_get_non_existence_info()
    {
        assertNull($this->moduleInfo->__get('girlfriend'));
        assertNull($this->moduleInfo->girlfriend);
    }

    public function test_set_info()
    {
        foreach ($this->info as $key => $value) {
            $this->moduleInfo->__set($key, 'another value');

            assertSame('another value', $this->moduleInfo->__get($key));
        }

        foreach ($this->info as $key => $value) {
            $this->moduleInfo->{$key} = 'another value';

            assertSame('another value', $this->moduleInfo->{$key});
        }
    }
}
