<?php

namespace SilexStarter\Menu;

class MenuContainer{
    protected $menus;
    protected $renderer;

    public function __construct(){
        $this->renderer = null;
        $this->menus    = [];
    }

    public function addItem(MenuItem $menu){
        $this->menus[$menu->getName()] = $menu;
    }

    public function getItem($name){
        return $this->menus[$name];
    }

    public function render($option){
        if(!is_null($this->renderer)){
            $renderer = $this->renderer;

            $renderer($this->menus, $option);
        }

        return $this->builtInRenderer($option);
    }

    public function setRenderer(\Closure $renderer){
        $this->renderer = $renderer;
    }

    protected function builtInRenderer($option){

    }
}