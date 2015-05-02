<?php

namespace SilexStarter\Config;

use Silex\Application;
use ArrayAccess;
use Exception;
use InvalidArgumentException;

class ConfigurationContainer implements ArrayAccess
{
    protected $app;
    protected $basePath;
    protected $config = [];
    protected $configPath = [];
    protected $namespacedPath = [];
    protected $namespacedConfig = [];

    /**
     * ConfigurationContainer constructor.
     *
     * @param Silex\Application $app      instance of Silex Application
     * @param string            $basePath the base path where configuration file located
     */
    public function __construct(Application $app, $basePath)
    {
        $this->basePath = rtrim($basePath, '/');
        $this->app = $app;
    }

    /**
     * Load the configuration file or array and save the value into array container.
     *
     * @param mixed  $config    filename or @namespace.filename, or any data type
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

        if ($configKey == 'app') {
            foreach ($config as $param => $value) {
                $this->app[$param] = $value;
            }

            return;
        }

        $this->config[$configKey] = $config;
    }

    public function loadNamespacedFile($config, $configKey, $namespace)
    {
        $this->namespacedConfig[$namespace][$configKey] = $config;
    }

    /**
     * Load the configuration from a file.
     *
     * @param string $file      the config file path
     * @param string $configKey the configuration key for access it outside container
     */
    public function loadFile($file, $configKey = '')
    {

        if (!$configKey) {
            $fragment = explode('.', $file);
            $configKey = ($this->isNamespaced($file)) ? $fragment[1] : $fragment[0] ;
        }

        $filePath = $this->resolvePath($file);

        if (!isset($this->config[$configKey])) {
            $configuration = require($filePath);

            $this->loadConfig($configuration, $configKey);
        }
    }

    /**
     * Resolve the configuration file path.
     *
     * @param string $file  the file path or namespaced file path
     *
     * @return string       the proper file path
     */
    protected function resolvePath($file)
    {
        $filename = ('.php' === substr($file, -4, 4)) ? $file : $file.'.php';

        if ($this->isNamespaced($file)) {
            return $this->resolveNamespacedPath($filename);
        }

        /* try to load the configuration file from the basepath */
        if (file_exists($this->basePath . '/' . $filename)) {
            return $this->basePath . '/' . $filename;
        }

        foreach ($this->configPath as $configPath) {
            if (file_exists($configPath . '/' . $filename)) {
                return $configPath . '/' . $filename;
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
        $namespace = $this->getNamespace($file);
        $file = $this->getKey($file);

        $publishedPath = $this->basePath . '/' . $namespace . '/' . $file;
        $originalPath = $this->namespacedPath[$namespace] . '/' . $file;

        /* try to load published config */
        if (file_exists($publishedPath)) {
            return $publishedPath;
        }

        /* load the config from the module namespace */
        if (file_exists($originalPath)) {
            return $originalPath;
        }

        throw new Exception("Can not resolve the path of $file in $namespace namespace");

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

    /**
     * Check if key is contain namespace.
     * @param  string  $key The configuration key
     *
     * @return boolean
     */
    protected function isNamespaced($key)
    {
        return (bool) $this->getNamespace($key);
    }

    /**
     * Get namespace out of the config key.
     *
     * @param  string $key The full config key
     *
     * @return string      The namespace
     */
    protected function getNamespace($key)
    {
        $namespace = [];
        preg_match("/@(.*?)\./s", $key, $namespace);

        return (empty($namespace)) ? false : $namespace[1];
    }

    protected function getKey($key)
    {
        return preg_replace("/@(.*?)\./s", '', $key);
    }

    /**
     * Convert dot notation into array access (key.subkey => [key][subkey]).
     *
     * @param  string $key   The dit delimited string
     * @param  array  $array The array
     *
     * @return mixed
     */
    protected function parseDotNotation($key, array $array)
    {
        $keys       = explode('.', $key);
        $configVal  = null;

        foreach ($keys as $count => $key) {
            if (0 == $count) {
                $configVal = $array[$key];
            } elseif (is_array($configVal) && isset($configVal[$key])) {
                $configVal = $configVal[$key];
            } else {
                throw new Exception("'{$keys[$count - 1]}' doesn't have '$key' sub configuration", 1);
            }
        }

        return $configVal;
    }

    /**
     * Get the configuration value on the specific key.
     *
     * @param  string $key The configuration key
     *
     * @return mixed
     */
    public function get($key)
    {
        if ($this->isNamespaced($key)) {
            $namespace  = $this->getNamespace($key);
            $key        = $this->getKey($key);

            return $this->getNamespacedValue($namespace, $key);
        } else {
            return $this->getValue($key);
        }
    }

    /**
     * Get config value of the specific key.
     *
     * @param  string $key The configuration key
     *
     * @return mixed
     */
    protected function getValue($key)
    {
        return $this->parseDotNotation($key, $this->config);
    }

    /**
     * Get config value from specific namespace.
     *
     * @param  string $namespace The config namespace
     * @param  string $key       The config key.
     *
     * @return mixed
     */
    protected function getNamespacedValue($namespace, $key)
    {
        return $this->parseDotNotation($key, $this->namespacedConfig[$namespace]);
    }

    /**
     * Set the value of specific key in the configuration.
     *
     * @param string $key   The configuration key
     * @param mixed  $value The configuration value
     */
    public function set($key, $value)
    {
        if ($this->isNamespaced($key)) {
            $namespace  = $this->getNamespace($key);
            $key        = $this->getKey($key);

            $this->setNamespacedValue($namespace, $key, $value);
        } else {
            $this->setValue($key, $value);
        }
    }

    /**
     * Set the value of specified config key.
     *
     * @param string $key   The configuration key
     * @param mixed  $value The configuration value
     */
    protected function setValue($key, $value, array &$config = null)
    {
        $key  = explode('.', $key); //[test]
        $keyLength = count($key) - 1;

        if (is_null($config)) {
            $config = &$this->config;
        }

        foreach ($key as $counter => $offsetKey) {
            if (!isset($config[$offsetKey]) && $counter != $keyLength) {
                $config[$offsetKey] = [];
            }

            $config = &$config[$offsetKey];
        }

        $config = $value;
    }

    /**
     * Set the value of specified config key inside namespace.
     *
     * @param string $namespace The configuration namespace
     * @param string $key       The configuration key
     * @param mixed  $value     The configuration value
     */
    protected function setNamespacedValue($namespace, $key, $value)
    {
        if (!isset($this->namespacedConfig[$namespace])) {
            $this->namespacedConfig[$namespace] = [];
        }

        $this->setValue($key, $value, $this->namespacedConfig[$namespace]);
    }

    /**
     * remove configuration key from the configuration container.
     *
     * @param string $offset the configuration key
     */
    public function remove($key)
    {
        $this->offsetUnset($key);
    }


    /**
     * Array access interface, to check existing config key.
     *
     * @param  string $key the configuration key
     *
     * @return boolean
     */
    public function offsetExists($key)
    {
        try {
            $this->get($key);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function offsetGet($key)
    {
        return $this->get($key);
    }

    public function offsetSet($key, $value)
    {
        $this->set($key, $value);
    }

    public function offsetUnset($key)
    {
        $this->remove($key);
    }
}
