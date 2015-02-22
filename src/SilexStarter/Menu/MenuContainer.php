<?php

namespace SilexStarter\Menu;

class MenuContainer{
    protected $menuCollection;
    protected $renderer;

    public function __construct(){
        $this->renderer = null;
        $this->menuCollection    = [];
    }

    public function addItem(MenuItem $menu){
        $this->menuCollection[$menu->getName()] = $menu;
    }

    public function getItem($name){
        return $this->menuCollection[$name];
    }

    public function render($option){
        if(!is_null($this->renderer)){
            $renderer = $this->renderer;

            $renderer($this->menuCollection, $option);
        }

        return $this->builtInRenderer($option);
    }

    public function setRenderer(\Closure $renderer){
        $this->renderer = $renderer;
    }

    protected function builtInRenderer($option){

    }
}