<?php

namespace SomeNamespace;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use User;
use Sentry;

class OtherController{

    protected $response;
    protected $app;

    public function __construct(Response $response, \Silex\Application $app){
        $this->response = $response;
        $this->app = $app;
    }

    public function index(){
        Sentry::getuser();
        return $this->app['twig']->render('index.twig');
    }
}