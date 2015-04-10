<?php

namespace SilexStarter\Menu;

class MenuManager{

    protected $menuContainers = [];

    public function create($name){
        $this->menuContainers[$name] = new MenuContainer($name);

        return $this->menuContainers[$name];
    }

    public function get($name){
        return $this->menuContainers[$name];
    }

    public function render($name){
        return $this->menuContainers[$name]->render();
    }
}