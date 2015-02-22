<?php

namespace SilexStarter\Asset;

use Symfony\Component\HttpFoundation\Request;

class AssetManager{

    protected $basepath;
    protected $aliases;
    protected $request;
    protected $js   = [];
    protected $css  = [];

    public function __construct(Request $request, $basepath = ''){
        $this->request  = $request;
        $this->basepath = $basepath;
    }

    public function js($jsfile){
        $this->js[] = $jsfile;
    }

    public function css($cssfile){
        $this->css[] = $cssfile;
    }

    /**
     * alias for js method
     */
    public function javascript($jsfile){
        $this->js($jsfile);
    }

    /**
     * alias for css method
     */
    public function stylesheet($cssfile){
        $this->css($cssfile);
    }

    public function load($assetfile){
        if('.js' == substr($assetfile, -3, 3)){
            $this->js($assetfile);
        }

        if('.css' == substr($assetfile, -4, 4)){
            $this->css($assetfile);
        }
    }

    public function renderJs($file = null){
        $tagFormat = "<script src=\"%s\"></script>\n";

        $file = ($file) ? $file : $this->js;
        return $this->render($file, $tagFormat);
    }

    public function renderCss($file = null){
        $tagFormat = "<link rel=\"stylesheet\" type=\"text/css\" href=\"%s\">\n";

        $file = ($file) ? $file : $this->css;
        return $this->render($file, $tagFormat);
    }

    protected function render($file, $tagFormat){
        /** if file is array of file, render each file respectively */
        if(is_array($file)){
            $tag = '';
            foreach ($file as $cssfile) {
                $tag .= sprintf($tagFormat, $this->resolvePath($cssfile));
            }

            return $tag;
        }

        /** if file is single file, render immediately */
        if(!is_null($file)){
            return sprintf($tagFormat, $this->resolvePath($cssfile));
        }
    }

    protected function resolvePath($file, $absolute = false){
        $namespace = [];
        preg_match("/@(.*?)\//s", $file, $namespace);

        /** if namespace exists, resolve the namespace */
        if($namespace){
            $file = str_replace($namespace[0], $this->basepath . '/' . $namespace[1] . '/', $file);
        }

        /** if refer to external path, return immediately (begin with //, http://, https://) */
        if('//' == substr($file, 0, 2) || 'http:' == substr($file, 0, 5) || 'https:' == substr($file, 0, 6)){
            return $file;
        }

        return (($absolute) ? $this->request->getScheme() . '://' . $this->request->getHost() : '') . $this->request->getBasePath() . '/' . ltrim($file, '/');
    }

}