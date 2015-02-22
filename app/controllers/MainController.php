<?php

class MainController{
    protected $repo;

    public function __construct(Silex\Application $app, SomeNamespace\OtherController $repo){
        $this->repo = $repo;
    }

    public function index(){
        return 'huray! controller is active <br />'.$this->repo->index();
    }

    public function sayMyName($name){
        return 'Your name is '.$name;
    }
}