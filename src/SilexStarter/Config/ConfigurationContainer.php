<?php

namespace SilexStarter\Config;

use Silex\Application;
use ArrayAccess;

class ConfigurationContainer implements ArrayAccess{

    protected $config;
    protected $app;
    protected $basepath;

    /**
     * ConfigurationContainer constructor
     * @param Application $app      [instance of Silex Application]
     * @param string      $basepath [the path where configuration file located]
     */
    public function __construct(Application $app, $basepath){
        $this->basepath = rtrim($basepath, DIRECTORY_SEPARATOR);
        $this->config   = [];
        $this->app      = $app;
    }

    /**
     * Load the configuration file and save the value into array container
     * @param  [string] $file       [description]
     * @param  [string] $configKey  [description]
     * @return [void]               [description]
     */
    public function load($file, $configKey = null){
        $file       = ('.php' == substr($file, -4, 4)) ? $file : $file.'.php';
        $configKey  = (!$configKey) ? explode('.', $file)[0] : $configKey;
        $filePath   = $this->basepath.'/'.$file;

        /** return immediately when config already loaded */
        if(isset($this->config[$configKey])){
            return;
        }

        if(!file_exists($filePath)){
            throw new \Exception("Configuration file [$filePath] can not be found", 1);
        }

        if($configKey == 'app'){
            $configuration = require($filePath);

            foreach ($configuration as $param => $value) {
                $this->app[$param] = $value;
            }

            return;
        }

        if(!isset($this->config[$configKey])){
            $this->config[$configKey] = require($filePath);
        }
    }

    public function unload($offset){
        $this->offsetUnset($offset);
    }

    /**
     * [loadDirectory description]
     * @param  [type] $dir [description]
     * @return [type]      [description]
     */
    public function loadDirectory($dir = null){

    }

    public function set($offset, $value){
        $this->offsetSet($offset, $value);
    }

    public function get($offset){
        return $this->offsetGet($offset);
    }

    public function offsetExists ($offset){
        return isset($this->config[$offset]);
    }

    public function offsetGet ( $offset ){
        $offsetChunk = explode('.', $offset);

        /**
         * if xxx.yyy.zzz offset is exist, return it immediately
         * else, try to search deeply into configuration array as xxx[yyy][zzz]
         */
        if(isset($this->config[$offset])){
            return $this->config[$offset];
        }

        /** support module:file.key.subkey */
        $offsetChunk[0] = str_replace(':', '/', $offsetChunk[0]);

        /** if not set, try to load the config file */
        if(!isset($this->config[$offsetChunk[0]])){
            $this->load($offsetChunk[0]);
        }

        if(count($offsetChunk) == 1){
            return $this->config[$offsetChunk[0]];
        }

        $configVal = null;

        foreach ($offsetChunk as $count => $chunk) {
            if(0 == $count){
                $configVal = $this->config[$chunk];
            }else if (is_array($configVal) && isset($configVal[$chunk])){
                $configVal = $configVal[$chunk];
            }else{
                throw new \Exception("'{$offsetChunk[$count-1]}' doesn't have '$chunk' sub configuration", 1);

            }
        }

        return $configVal;
    }

    public function offsetSet ( $offset , $value ){
        $offsetChunk = explode('.', $offset);
        $offsetLength= count($offsetChunk) - 1;

        if(!count($offsetChunk) > 1){
            $this->config[$offset] = $value;
            return;
        }

        if(!isset($this->config[$offsetChunk[0]])){
            try{
                $this->load($offsetChunk[0]);
            }catch(\Exception $e){
                $this->config[$offsetChunk[0]] = [];
            }
        }

        $config = &$this->config;

        foreach ($offsetChunk as $counter => $offsetKey) {
            if(!isset($config[$offsetKey]) && $counter != $offsetLength){
                $config[$offsetKey] = [];
            }

            $config = &$config[$offsetKey];
        }

        $config = $value;
    }

    public function offsetUnset ( $offset ){
        unset($this->config[$offset]);
    }

}