<?php

namespace SilexStarter\Asset;

use PHPUnit_Framework_TestCase;

class AssetManagerTest extends PHPUnit_Framework_TestCase
{
    protected $asset;

    public function setUp()
    {
        $request = $this->getMock('Symfony\Component\HttpFoundation\Request');
        $stack   = $this->getMock('Symfony\Component\HttpFoundation\RequestStack');

        $request->method('getHost')
                ->willReturn('www.somehost.com');

        $request->method('getScheme')
                ->willReturn('http');

        $stack->method('getCurrentRequest')
              ->willReturn($request);

        $this->asset = new AssetManager($stack, 'assets');
    }

    public function tearDown()
    {
        $this->asset = null;
    }

    /**
     * @covers SilexStarter\Asset\AssetManager::javascript
     */
    public function test_load_js_file()
    {
        $this->asset->javascript('somefile.js');
        assertEquals(
            "<script src=\"/assets/somefile.js\"></script>\n",
            $this->asset->renderJs()
        );
    }

    /**
     * @covers SilexStarter\Asset\AssetManager::stylesheet
     */
    public function test_load_css_file()
    {
        $this->asset->stylesheet('style.css');
        assertEquals(
            "<link rel=\"stylesheet\" type=\"text/css\" href=\"/assets/style.css\">\n",
            $this->asset->renderCss()
        );
    }

    /**
     * @covers SilexStarter\Asset\AssetManager::javascript
     */
    public function test_load_multiple_js_file()
    {
        $this->asset->javascript(['somefile.js', 'anotherfile.js']);
        assertEquals(
            "<script src=\"/assets/somefile.js\"></script>\n".
            "<script src=\"/assets/anotherfile.js\"></script>\n",
            $this->asset->renderJs()
        );
    }

    /**
     * @covers SilexStarter\Asset\AssetManager::stylesheet
     */
    public function test_load_multiple_css_file()
    {
        $this->asset->stylesheet(['bootstrap.css', 'style.css']);
        assertEquals(
            "<link rel=\"stylesheet\" type=\"text/css\" href=\"/assets/bootstrap.css\">\n".
            "<link rel=\"stylesheet\" type=\"text/css\" href=\"/assets/style.css\">\n",
            $this->asset->renderCss()
        );
    }

    /**
     * @covers SilexStarter\Asset\AssetManager::javascript
     */
    public function test_load_namespaced_js_file()
    {
        $this->asset->javascript('@namespace/external.js');
        assertEquals(
            "<script src=\"/assets/namespace/external.js\"></script>\n",
            $this->asset->renderJs()
        );
    }

    /**
     * @covers SilexStarter\Asset\AssetManager::stylesheet
     */
    public function test_load_namespaced_css_file()
    {
        $this->asset->stylesheet('@namespace/style.css');
        assertEquals(
            "<link rel=\"stylesheet\" type=\"text/css\" href=\"/assets/namespace/style.css\">\n",
            $this->asset->renderCss()
        );
    }

    /**
     * @covers SilexStarter\Asset\AssetManager::javascript
     */
    public function test_load_external_js_file()
    {
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

    /**
     * @covers SilexStarter\Asset\AssetManager::stylesheet
     */
    public function test_load_external_css_file()
    {
        $this->asset->stylesheet([
            'http://cdn.somehost.com/style.css',
            'https://cdn.somehost.com/style.css',
        ]);
        assertEquals(
            "<link rel=\"stylesheet\" type=\"text/css\" href=\"http://cdn.somehost.com/style.css\">\n".
            "<link rel=\"stylesheet\" type=\"text/css\" href=\"https://cdn.somehost.com/style.css\">\n",
            $this->asset->renderCss()
        );
    }

    /**
     * @covers SilexStarter\Asset\AssetManager::load
     */
    public function test_load_wildcard_js_file()
    {
        $this->asset->load('@namespace/external.js');
        assertEquals(
            "<script src=\"/assets/namespace/external.js\"></script>\n",
            $this->asset->renderJs()
        );
    }

    /**
     * @covers SilexStarter\Asset\AssetManager::stylesheet
     */
    public function test_load_wildcard_css_file()
    {
        $this->asset->load('@namespace/style.css');
        assertEquals(
            "<link rel=\"stylesheet\" type=\"text/css\" href=\"/assets/namespace/style.css\">\n",
            $this->asset->renderCss()
        );
    }

    /**
     * @covers SilexStarter\Asset\AssetManager::resolvePath
     */
    public function test_resolve_external_path()
    {
        assertEquals(
            'http://cdn.somehost.com/style.css',
            $this->asset->resolvePath('http://cdn.somehost.com/style.css')
        );
    }

    /**
     * @covers SilexStarter\Asset\AssetManager::resolvePath
     */
    public function test_resolve_namespaced_path()
    {
        assertEquals(
            '/assets/namespace/style.css',
            $this->asset->resolvePath('@namespace/style.css')
        );

        assertEquals(
            '/assets/namespace/subdir/style.css',
            $this->asset->resolvePath('@namespace/subdir/style.css')
        );
    }

    /**
     * @covers SilexStarter\Asset\AssetManager::resolvePath
     */
    public function test_resolve_path()
    {
        assertEquals(
            '/assets/style.css',
            $this->asset->resolvePath('style.css')
        );

        assertEquals(
            '/assets/subdir/style.css',
            $this->asset->resolvePath('subdir/style.css')
        );
    }

    /**
     * @covers SilexStarter\Asset\AssetManager::resolvePath
     */
    public function test_resolve_absolute_path()
    {
        assertEquals(
            'http://www.somehost.com/assets/style.css',
            $this->asset->resolvePath('style.css', true)
        );

        assertEquals(
            'http://www.somehost.com/assets/subdir/style.css',
            $this->asset->resolvePath('subdir/style.css', true)
        );
    }

    /**
     * @covers SilexStarter\Asset\AssetManager::resolvePath
     */
    public function test_resolve_namespaced_absolute_path()
    {
        assertEquals(
            'http://www.somehost.com/assets/namespace/style.css',
            $this->asset->resolvePath('@namespace/style.css', true)
        );

        assertEquals(
            'http://www.somehost.com/assets/namespace/subdir/style.css',
            $this->asset->resolvePath('@namespace/subdir/style.css', true)
        );
    }
}
