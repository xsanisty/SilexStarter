<?php

namespace SilexStarter\Menu;

class ChildMenuContainer extends MenuContainer
{
    protected $parent;

    public function __construct(MenuItem $parent)
    {
        $this->parent = $parent;
    }

    public function createItem($name, array $attributes)
    {
        $attributes['name'] = $name;
        $this->items[$name] = new MenuItem($attributes);
        $this->items[$name]->setLevel($this->level);

        return $this->items[$name];
    }

    public function addItem(MenuItem $menu)
    {
        $menu->setLevel($this->level);
        $this->items[$menu->getName()] = $menu;
    }

    /**
     * [hasItem description].
     *
     * @return bool [description]
     */
    public function hasItem()
    {
        return count($this->items) !== 0;
    }
}
