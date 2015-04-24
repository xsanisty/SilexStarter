<?php

namespace SilexStarter\Config;

use PHPUnit_Framework_TestCase;

class ConfigurationContainerTest extends PHPUnit_Framework_TestCase
{
    protected $config;
    protected $app;

    public function setUp()
    {
        $this->app  = $this->getMockBuilder('Silex\Application')
                     ->disableOriginalConstructor()
                     ->getMock();


        $this->config = new ConfigurationContainer($this->app, 'assets');
    }

    public function tearDown()
    {
        $this->config = null;
    }

    public function test_get_config()
    {
        $this->config->set('test', 'testValue');

        assertEquals('testValue', $this->config->get('test'));
    }

    public function test_get_multi_level_config(){
        $this->config->set('deep.buried.under.thegrave', 'dead bodies');

        assertEquals($this->config->get('deep.buried.under.thegrave'), 'dead bodies');
        assertEquals($this->config->get('deep.buried.under'), ['thegrave' => 'dead bodies']);
        assertEquals($this->config->get('deep.buried'), ['under' => ['thegrave' => 'dead bodies']]);
        assertEquals($this->config->get('deep'), ['buried' => ['under' => ['thegrave' => 'dead bodies']]]);

    }

    public function test_load_config(){
        $config = [
            'key_one'   => 'one',
            'key_two'   => 'two',
            'level'     => [
                'one' => 'level one',
                'two' => 'level two',
                'sub_level' => [
                    'one' => 'sub level one',
                    'two' => 'sub level two'
                ],
            ]
        ];

        $this->config->load($config, 'someKey');

        assertEquals($this->config->get('someKey'), $config);
        assertEquals($this->config->get('someKey.key_one'), $config['key_one']);
        assertEquals($this->config->get('someKey.level'), $config['level']);
        assertEquals($this->config->get('someKey.level.one'), $config['level']['one']);
        assertEquals($this->config->get('someKey.level.sub_level'), $config['level']['sub_level']);
        assertEquals($this->config->get('someKey.level.sub_level.one'), $config['level']['sub_level']['one']);
    }

}