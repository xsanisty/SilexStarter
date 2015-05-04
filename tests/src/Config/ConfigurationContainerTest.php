<?php

namespace SilexStarter\Config;

use PHPUnit_Framework_TestCase;
use ReflectionClass;

class ConfigurationContainerTest extends PHPUnit_Framework_TestCase
{
    protected $basedir;
    protected $config;
    protected $app;

    protected function getProtected($methodName)
    {
        $class  = new ReflectionClass($this->config);
        $method = $class->getMethod($methodName);
        $method->setAccessible(true);

        return $method;
    }

    public function setUp()
    {
        $this->app  = $this->getMockBuilder('Silex\Application')
                     ->disableOriginalConstructor()
                     ->getMock();

        $this->basedir = __DIR__ . '/../stubs/test_config';

        $this->config = new ConfigurationContainer($this->app, $this->basedir);
    }

    public function tearDown()
    {
        $this->config = null;
    }

    /**
     * @covers SilexStarter\Config\ConfigurationContainer::parseDotNotation
     */
    public function test_parse_dot_notation(){
        $testConfig = [
            'somekey' => [
                'level_1' => [
                    'sub_level_1' => 'value'
                ]
            ]
        ];

        assertSame(
            $testConfig['somekey']['level_1']['sub_level_1'],
            $this->getProtected('parseDotNotation')->invokeArgs($this->config, ['somekey.level_1.sub_level_1', $testConfig])
        );

        assertSame(
            $testConfig['somekey']['level_1'],
            $this->getProtected('parseDotNotation')->invokeArgs($this->config, ['somekey.level_1', $testConfig])
        );

        assertSame(
            $testConfig['somekey'],
            $this->getProtected('parseDotNotation')->invokeArgs($this->config, ['somekey', $testConfig])
        );
    }

    public function test_get_namespace()
    {
        assertSame(
            'namespace',
            $this->getProtected('getNamespace')->invokeArgs($this->config, ['@namespace.config.key'])
        );

        assertFalse($this->getProtected('getNamespace')->invokeArgs($this->config, ['namespace.config.key']));
    }

    public function test_get_key()
    {
        assertSame(
            'config.key',
            $this->getProtected('getKey')->invokeArgs($this->config, ['@namespace.config.key'])
        );

        assertSame(
            'namespace.config.key',
            $this->getProtected('getKey')->invokeArgs($this->config, ['namespace.config.key'])
        );
    }

    public function test_check_namespaced_key()
    {
        assertFalse(
            $this->getProtected('isNamespaced')->invokeArgs($this->config, ['nonamespace.config.key'])
        );
        assertTrue(
            $this->getProtected('isNamespaced')->invokeArgs($this->config, ['@namespace.config.key'])
        );
    }

    public function test_get_config()
    {
        $this->config->set('test', 'testValue');

        assertEquals('testValue', $this->config->get('test'));
    }

    public function test_get_multilevel_config()
    {
        $this->config->set('deep.buried.under.thegrave', 'dead bodies');

        assertEquals('dead bodies', $this->config->get('deep.buried.under.thegrave'));
        assertEquals(['thegrave' => 'dead bodies'], $this->config->get('deep.buried.under'));
        assertEquals(['under' => ['thegrave' => 'dead bodies']], $this->config->get('deep.buried'));
        assertEquals(['buried' => ['under' => ['thegrave' => 'dead bodies']]], $this->config->get('deep'));

    }

    public function test_get_namespaced_config()
    {
        $this->config->set('@namespace.test', 'testValue');

        assertEquals('testValue', $this->config->get('@namespace.test'));
    }

    public function test_get_namespaced_multilevel_config()
    {
        $this->config->set('@namespace.deep.buried.under.thegrave', 'dead bodies');

        assertEquals('dead bodies', $this->config->get('@namespace.deep.buried.under.thegrave'));
        assertEquals(['thegrave' => 'dead bodies'], $this->config->get('@namespace.deep.buried.under'));
        assertEquals(['under' => ['thegrave' => 'dead bodies']], $this->config->get('@namespace.deep.buried'));
        assertEquals(['buried' => ['under' => ['thegrave' => 'dead bodies']]], $this->config->get('@namespace.deep'));

    }

    /**
     * @expectedException        Exception
     * @expectedExceptionMessage Namespace nonexistence is not registered
     */
    public function test_get_nonexistent_namespace()
    {
        $this->config->get('@nonexistence.imaginary.config');
    }

    public function test_load_config()
    {
        $this->config->addDirectory(__DIR__ . '/../stubs/unpublished_namespace', 'namespace');
        $this->config->load('sample');
        $this->config->load('@namespace.sample');

        assertSame('test_value', $this->config->get('sample.test_key'));
        assertSame('test_value', $this->config->get('@namespace.sample.test_key'));
    }

    public function test_resolve_path()
    {
        assertSame(
            $this->basedir . '/' . 'sample.php',
            $this->getProtected('resolvePath')->invokeArgs($this->config, ['sample'])
        );
    }

    /**
     * @covers SilexStarter\Config\ConfigurationContainer::resolvePath
     */
    public function test_resolve_unpublished_namespace_path()
    {
        $this->config->addDirectory(__DIR__ . '/../stubs/unpublished_namespace', 'some-ns');
        assertSame(
            __DIR__ . '/../stubs/unpublished_namespace/sample.php',
            $this->getProtected('resolvePath')->invokeArgs($this->config, ['@some-ns.sample'])
        );
    }

    /**
     * @covers SilexStarter\Config\ConfigurationContainer::resolveNamespacedPath
     */
    public function test_resolve_namespaced_path()
    {
        /* this expected to locate basedir/namespace because basedir/namespace is exists */
        $this->config->addDirectory(__DIR__ . '/../stubs/unpublished_namespace', 'namespace');
        assertSame(
            $this->basedir . '/namespace/sample.php',
            $this->getProtected('resolvePath')->invokeArgs($this->config, ['@namespace.sample'])
        );
    }

}