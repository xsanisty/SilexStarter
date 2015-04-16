<?php

use SilexStarter\Asset\AssetManager;

class AssetManagerTest extends PHPUnit_Framework_TestCase{

    protected $asset;

    public function setUp(){
        $this->asset = new AssetManager(
            $this->getMock('Symfony\Component\HttpFoundation\Request'),
            'assets'
        );
    }

    public function tearDown(){
        $this->asset = null;

    }

    public function test_load_js_file(){
        $this->asset->javascript('somefile.js');
        assertEquals(
            "<script src=\"/assets/somefile.js\"></script>\n",
            $this->asset->renderJs('somefile.js')
        );

    }

    public function test_load_css_file(){
        $this->asset->stylesheet('style.css');
        assertEquals(
            "<link rel=\"stylesheet\" type=\"text/css\" href=\"/assets/style.css\">\n",
            $this->asset->renderCss()
        );
    }

    public function test_load_multiple_js_file(){

        $this->asset->javascript(['somefile.js', 'anotherfile.js']);
        assertEquals(
            "<script src=\"/assets/somefile.js\"></script>\n".
            "<script src=\"/assets/anotherfile.js\"></script>\n",
            $this->asset->renderJs()
        );
    }

    public function test_load_multiple_css_file(){
        $this->asset->stylesheet(['bootstrap.css', 'style.css']);
        assertEquals(
            "<link rel=\"stylesheet\" type=\"text/css\" href=\"/assets/bootstrap.css\">\n".
            "<link rel=\"stylesheet\" type=\"text/css\" href=\"/assets/style.css\">\n",
            $this->asset->renderCss()
        );
    }

    public function test_load_namespaced_js_file(){

        $this->asset->javascript('@namespace/external.js');
        assertEquals(
            "<script src=\"/assets/namespace/external.js\"></script>\n",
            $this->asset->renderJs()
        );
    }

    public function test_load_namespaced_css_file(){
        $this->asset->stylesheet('@namespace/style.css');
        assertEquals(
            "<link rel=\"stylesheet\" type=\"text/css\" href=\"/assets/namespace/style.css\">\n",
            $this->asset->renderCss()
        );
    }

    public function test_load_external_js_file(){

        $this->asset->javascript([
            'http://cdn.somehost.com/external.js',
            'https://cdn.somehost.com/external.js',
        ]);
        assertEquals(
            "<script src=\"http://cdn.somehost.com/external.js\"></script>\n".
            "<script src=\"https://cdn.somehost.com/external.js\"></script>\n",
            $this->asset->renderJs()
        );
    }

    public function test_load_external_css_file(){

        $this->asset->stylesheet([
            'http://cdn.somehost.com/style.css',
            'https://cdn.somehost.com/style.css'
        ]);
        assertEquals(
            "<link rel=\"stylesheet\" type=\"text/css\" href=\"http://cdn.somehost.com/style.css\">\n".
            "<link rel=\"stylesheet\" type=\"text/css\" href=\"https://cdn.somehost.com/style.css\">\n",
            $this->asset->renderCss()
        );
    }

    public function test_load_wildcard_js_file(){
        $this->asset->load('@namespace/external.js');
        assertEquals(
            "<script src=\"/assets/namespace/external.js\"></script>\n",
            $this->asset->renderJs()
        );
    }

    public function test_load_wildcard_css_file(){
        $this->asset->load('@namespace/style.css');
        assertEquals(
            "<link rel=\"stylesheet\" type=\"text/css\" href=\"/assets/namespace/style.css\">\n",
            $this->asset->renderCss()
        );
    }
}