<?php

namespace SilexStarter\Menu;

class ChildMenuContainer extends MenuContainer
{
    /**
     * Parent Item
     *
     * @var SilexStarter\Menu\MenuItem
     */
    protected $parent;

    /**
     * Build the child container object.
     *
     * @param MenuItem $parent parent item
     */
    public function __construct(MenuItem $parent)
    {
        $this->parent = $parent;
    }

    /**
     * Create new MenuItem object inside the MenuContainer.
     *
     * @param string $name       MenuItem name
     * @param array  $attributes MenuItem attributes
     *
     * @return SilexStarter\Menu\MenuItem
     */
    public function createItem($name, array $attributes)
    {
        $attributes['name'] = $name;
        $this->items[$name] = new MenuItem($attributes);
        $this->items[$name]->setLevel($this->level);

        return $this->items[$name];
    }

    /**
     * Add new MenuItem object into container item lists.
     *
     * @param SilexStarter\Menu\MenuItem $menu MenuItem object
     */
    public function addItem(MenuItem $menu)
    {
        $menu->setLevel($this->level);
        $this->items[$menu->getName()] = $menu;
    }

    /**
     * Check if current menu container has item in it.
     *
     * @return bool
     */
    public function hasItem()
    {
        return count($this->items) !== 0;
    }
}
