<?php

namespace SilexStarter\Router;

class RouteMap
{
    protected $pattern;
    protected $httpMethod;
    protected $action;
    protected $options;

    public function __construct($httpMethod = 'get', $pattern = '/', $action = null, array $options = [])
    {
        $this->setPattern($pattern);
        $this->setHttpMethod($httpMethod);
        $this->setAction($action);
        $this->setOptions($options);
    }

    public function getPattern()
    {
        return $this->pattern;
    }

    public function getHttpMethod()
    {
        return $this->httpMethod;
    }

    public function getAction()
    {
        return $this->action;
    }

    public function getOptions()
    {
        return $this->options;
    }

    public function setPattern($pattern)
    {
        $this->pattern = '/'.ltrim($pattern, '/');
    }

    public function setHttpMethod($httpMethod)
    {
        if ($this->isValidHttpMethod($httpMethod)) {
            $this->httpMethod = $httpMethod;
        }
    }

    public function setAction($action)
    {
        $this->action = $action;
    }

    public function setOptions(array $options)
    {
        $this->options = $options;
    }

    protected function isValidHttpMethod($method)
    {
        return in_array(strtolower($method), ['get', 'post', 'put', 'delete', 'head', 'options', 'patch']);
    }
}
