<?php

namespace SilexStarter\Config;

use Silex\Application;
use ArrayAccess;
use Exception;
use InvalidArgumentException;

class ConfigurationContainer implements ArrayAccess
{
    protected $config;
    protected $app;
    protected $basePath;
    protected $configPath = [];
    protected $namespacedPath = [];

    /**
     * ConfigurationContainer constructor.
     *
     * @param Silex\Application $app      instance of Silex Application
     * @param string            $basePath the base path where configuration file located
     */
    public function __construct(Application $app, $basePath)
    {
        $this->basePath     = rtrim($basePath, '/');
        $this->config       = [];
        $this->app          = $app;
    }

    /**
     * Load the configuration file or array and save the value into array container.
     *
     * @param mixed  $config    filename or namespace::filename, or any data type
     * @param string $configKey override the config key, if not specified, the filename will be used
     */
    public function load($config, $configKey = '')
    {
        /* return immediately when config already loaded */
        if (isset($this->config[$configKey])) {
            return;
        }

        if (is_string($config)) {
            $this->loadFile($config, $configKey);
        } else {
            $this->loadConfig($config, $configKey);
        }
    }

    /**
     * Load the configuration from an array, object, etc.
     *
     * @param mixed  $config    the array, object or any type of data
     * @param string $configKey the configuration key for access it outside container
     */
    public function loadConfig($config, $configKey)
    {
        if (!$configKey) {
            throw new InvalidArgumentException('Config key can not be empty');
        }

        $this->config[$configKey] = $config;
    }

    /**
     * Load the configuration from a file.
     *
     * @param string $file      the config file path
     * @param string $configKey the configuration key for access it outside container
     */
    public function loadFile($file, $configKey = '')
    {
        $configKey  = (!$configKey) ? explode('.', $file)[0] : $configKey;

        $filePath = $this->resolvePath($file);
        if ($configKey == 'app') {
            $configuration = require $filePath;

            foreach ($configuration as $param => $value) {
                $this->app[$param] = $value;
            }

            return;
        }

        if (!isset($this->config[$configKey])) {
            $this->config[$configKey] = require($filePath);
        }
    }

    /**
     * Resolve the configuration file path.
     *
     * @param string $file The file path or namespaced file path
     *
     * @return string The proper file path
     */
    protected function resolvePath($file)
    {
        $filename   = ('.php' === substr($file, -4, 4)) ? $file : $file.'.php';

        if(strpos($file, '::') > -1){
            return $this->resolveNamespacedPath($filename);
        }

        /* try to load the configuration file from the basepath */
        if (file_exists($this->basePath.'/'.$filename)) {
            return $this->basePath.'/'.$filename;
        }

        foreach ($this->configPath as $configPath) {
            if (file_exists($configPath.'/'.$filename)) {
                return $configPath.'/'.$filename;
            }
        }

        throw new Exception("Configuration file [$file] can not be found");

    }

    /**
     * Resolve file path within namespace.
     *
     * @param  string $file namespaced config file location
     *
     * @return string       the real path of the config file
     */
    protected function resolveNamespacedPath($file)
    {
        list($namespace, $filename) = explode('::', $file, 2);

        /* try to load published config */
        if (file_exists($this->basePath.'/'.$namespace.'/'.$filename)) {
            return $this->basePath.'/'.$namespace.'/'.$filename;
        }

        /* load the config from the module namespace */
        if (file_exists($this->namespacedPath[$namespace].'/'.$filename)) {
            return $this->namespacedPath[$namespace].'/'.$filename;
        }

        throw new Exception("Can not resolve the path of $filename in $namespace namespace");

    }

    /**
     * remove configuration key from the configuration container.
     *
     * @param string $offset the configuration key
     */
    public function unload($offset)
    {
        $this->offsetUnset($offset);
    }

    /**
     * add config directory into config  directory lists.
     *
     * @param string $directory the config directory
     * @param string $namespace config namespace
     */
    public function addDirectory($directory, $namespace = '')
    {
        $directory = rtrim($directory, '/');

        if (!$namespace) {
            $this->configPath[] = $directory;
        } else {
            $this->namespacedPath[$namespace] = $directory;
        }
    }

    public function set($offset, $value)
    {
        $this->offsetSet($offset, $value);
    }

    public function get($offset)
    {
        return $this->offsetGet($offset);
    }

    public function offsetExists($offset)
    {
        return isset($this->config[$offset]);
    }

    public function offsetGet($offset)
    {
        $offsetChunk = explode('.', $offset);
        $configFile  = $offsetChunk[0];

        /*
         * if xxx.yyy.zzz offset is exist, return it immediately
         * else, try to search deeply into configuration array as xxx[yyy][zzz]
         */
        if (isset($this->config[$offset])) {
            return $this->config[$offset];
        }

        /* if not set, try to load the config file */
        if (!isset($this->config[$configFile])) {
            $this->load($configFile);
        }

        /* if there is no dot notation, return the whole config */
        if (count($offsetChunk) == 1) {
            return $this->config[$configFile];
        }

        /* else, dive through the dot notation until it found */
        $configVal = null;

        foreach ($offsetChunk as $count => $chunk) {
            if (0 == $count) {
                $configVal = $this->config[$chunk];
            } elseif (is_array($configVal) && isset($configVal[$chunk])) {
                $configVal = $configVal[$chunk];
            } else {
                throw new Exception("'{$offsetChunk[$count - 1]}' doesn't have '$chunk' sub configuration", 1);
            }
        }

        return $configVal;
    }

    public function offsetSet($offset, $value)
    {
        $offsetChunk = explode('.', $offset);
        $offsetLength = count($offsetChunk) - 1;

        if (!count($offsetChunk) > 1) {
            $this->config[$offset] = $value;

            return;
        }

        if (!isset($this->config[$offsetChunk[0]])) {
            try {
                $this->load($offsetChunk[0]);
            } catch (Exception $e) {
                $this->config[$offsetChunk[0]] = [];
            }
        }

        $config = &$this->config;

        foreach ($offsetChunk as $counter => $offsetKey) {
            if (!isset($config[$offsetKey]) && $counter != $offsetLength) {
                $config[$offsetKey] = [];
            }

            $config = &$config[$offsetKey];
        }

        $config = $value;
    }

    public function offsetUnset($offset)
    {
        unset($this->config[$offset]);
    }
}
