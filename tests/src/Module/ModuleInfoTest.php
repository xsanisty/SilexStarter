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

    public function test_get_author_name()
    {
        assertSame($this->info['author_name'], $this->moduleInfo->__get('author_name'));
        assertSame($this->info['author_name'], $this->moduleInfo->author_name);
    }

    public function test_get_author_email()
    {
        assertSame($this->info['author_email'], $this->moduleInfo->__get('author_email'));
        assertSame($this->info['author_email'], $this->moduleInfo->author_email);
    }

    public function test_get_repository()
    {
        assertSame($this->info['repository'], $this->moduleInfo->__get('repository'));
        assertSame($this->info['repository'], $this->moduleInfo->repository);
    }

    public function test_get_website()
    {
        assertSame($this->info['website'], $this->moduleInfo->__get('website'));
        assertSame($this->info['website'], $this->moduleInfo->website);
    }

    public function test_get_name()
    {
        assertSame($this->info['name'], $this->moduleInfo->__get('name'));
        assertSame($this->info['name'], $this->moduleInfo->name);
    }

    public function test_get_description()
    {
        assertSame($this->info['description'], $this->moduleInfo->__get('description'));
        assertSame($this->info['description'], $this->moduleInfo->description);
    }
}
