<?php

namespace SilexStarter\Asset;

class AssetManager{

    protected $basepath;
    protected $js;
    protected $css;
    protected $aliases;

    public function __cosntruct($basepath = ''){
        $this->basepath = $basepath;
    }

    public function js($jsfile, $option = []){

    }

    public function javascript($jsfile, $option = []){
        $this->js($jsfile, $option);
    }

    public function css($cssfile, $option = []){

    }

    public function stylesheet($cssfile, $option = []){
        $this->css($cssfile, $option);
    }

}