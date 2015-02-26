<?php

namespace SilexStarter\Module;

class ModuleResource{

    protected $resources;
    protected $resourceFields = [
        'routes',
        'middlewares',
        'controllers',
        'views',
        'services',
        'config',
        'assets'
    ];

    public function __construct(array $resources){
        foreach ($this->resourceFields as $field) {
            $this->resources[$field] = isset($resources[$field]) ? $resources[$field] : null;
        }
    }

    /**
     * Resource getter, so it possible to access $object->resources
     * @param  string $resources    [description]
     * @return mixed                [description]
     */
    public function __get($resource){
        if(in_array($resource, $this->resourceFields)){
            return $this->resources[$resource];
        }

        return null;
    }

    /**
     * Resource setter, so it possible to assign value to resources using $object->resources = value
     * @param string $resources     [description]
     * @param mixeed $value         [description]
     */
    public function __set($resource, $value){
        if(in_array($resource, $this->resourceFields)){
            $this->resources[$resource] = $value;
        }
    }

}