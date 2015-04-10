<?php

namespace SilexStarter\Menu;

use InvalidArgumentException;

class MenuContainer{
    /**
     * List of menu item
     * @var SilexStarter\Menu\MenuItem
     */
    protected $items = [];

    /**
     * The menu renderer for rendering the collection
     * @var SilexStarter\Menu\MenuRenderer
     */
    protected $renderer;

    /**
     * The collection name
     * @var string
     */
    protected $name;

    /**
     * Nested menu level
     * @var integer
     */
    protected $level = 0;

    public function __construct($name){
        $this->name = $name;
    }

    public function getLevel(){
        return $this->level;
    }

    public function setLevel($level){
        $this->level = $level;
        foreach ($this->items as $item) {
            $item->setLevel($level);
        }
    }

    public function createItem($name, array $attributes){
        $attributes['name'] = $name;
        $this->items[$name] = new MenuItem($attributes);

        return $this->items[$name];
    }

    public function addItem(MenuItem $menu){
        $this->items[$menu->getName()] = $menu;
    }

    public function removeItem($name){
        if(isset($this->items[$name])){
            unset($this->items[$name]);
        }
    }

    public function hasItem(){
        return count($this->items) !== 0;
    }

    public function setActive($name){
        $names = explode('.', $name);
        $menu  = $this;
        $item  = null;

        foreach ($names as $name) {
            $item = $menu->getItem($name);
            $menu = $item->getChildren();
        }

        return $item->setActive(true);
    }

    public function getItem($name){
        if(isset($this->items[$name])){
            return $this->items[$name];
        }

        throw new Exception("Can not find menu with name $name", 1);

    }

    public function getItems(){
        return $this->items;
    }

    public function render(){
        if(!is_null($this->renderer)){
            return $this->renderer->render($this);
        }
    }

    public function setRenderer(MenuRendererInterface $renderer){
        $this->renderer = $renderer;
    }
}