<?php

namespace SilexStarter\Menu;

class MenuContainer{
    protected $menuCollection;
    protected $renderer;

    public function __construct(){
        $this->menuCollection    = [];
    }

    public function addItem(MenuItem $menu){
        $this->menuCollection[$menu->getName()] = $menu;
    }

    public function getItem($name){
        return $this->menuCollection[$name];
    }

    public function getItems(){
        return $this->menuCollection;
    }

    public function render($option){
        if(!is_null($this->renderer)){

            $this->renderer->render();
        }

        return $this->builtInRenderer($option);
    }

    public function setRenderer(MenuRenderer $renderer){
        $this->renderer = $renderer;
        $this->renderer->setMenu($this);
    }
}