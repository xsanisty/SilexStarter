<?php

class AutoRouteController{

    protected $app;

    public function __construct(Silex\Application $app){
        $this->app = $app;
    }

    public function getIndex(){
        return $this->app['twig']->render('index.twig');
    }

    public function getVars($params = null){
        var_dump($params);

        return 'wew';
    }

    public function putVars($params){
        return Response::json((array) $params);
    }
}