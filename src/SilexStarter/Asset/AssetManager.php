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

    public function js($jsfile, $option = array()){

    }

    public function javascript($jsfile, $option = array()){
        $this->js($jsfile, $option);
    }

    public function css($cssfile, $option = array()){

    }

    public function stylesheet($cssfile, $option = array()){
        $this->css($cssfile, $option);
    }

}