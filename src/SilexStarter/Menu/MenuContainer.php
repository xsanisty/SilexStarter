<?php

namespace SilexStarter\Menu;

use Exception;
use SilexStarter\Contracts\MenuRendererInterface;

class MenuContainer
{
    /**
     * List of menu item.
     *
     * @var array array of SilexStarter\Menu\MenuItem
     */
    protected $items = [];

    /**
     * The menu renderer for rendering the collection.
     *
     * @var SilexStarter\Menu\MenuRendererInterface
     */
    protected $renderer;

    /**
     * The collection name.
     *
     * @var string
     */
    protected $name;

    /**
     * Nested menu level.
     *
     * @var int
     */
    protected $level = 0;

    /**
     * Currently active menu
     * @var SilexStarter\Menu\MenuItem|null
     */
    protected $activeMenu = null;

    /**
     * Build the MenuCOntainer instance.
     *
     * @param string $name The menu collection name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * Get the current level of nested menu container, default is 0.
     *
     * @return int
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * Set the current level of menu container object.
     *
     * @param int $level The current level of menu container object
     */
    public function setLevel($level)
    {
        $this->level = $level;
        foreach ($this->items as $item) {
            $item->setLevel($level);
        }
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

        return $this->items[$name];
    }

    /**
     * Add new MenuItem object into container item lists.
     *
     * @param SilexStarter\Menu\MenuItem $menu MenuItem object
     */
    public function addItem(MenuItem $menu)
    {
        $this->items[$menu->getName()] = $menu;
    }

    /**
     * Remove MenuItem object from the container item lists.
     *
     * @param string $name MenuItem name
     */
    public function removeItem($name)
    {
        if (isset($this->items[$name])) {
            unset($this->items[$name]);
        }
    }

    /**
     * Set current active menu in container item list, this will deactivate the currently active menu.
     *
     * @param string $name MenuItem name or dot sparated for multi level menu item
     */
    public function setActive($name)
    {
        $names = explode('.', $name);
        $menu  = $this;
        $item  = null;

        foreach ($names as $name) {
            $item = $menu->getItem($name);
            $menu = $item->getChildren();
        }

        return $item->setActive(true);
    }

    /**
     * Get MenuItem object from the item list.
     *
     * @param string $name MenuItem name
     *
     * @return SilexStarter\Menu\MenuItem menu item object
     */
    public function getItem($name)
    {
        if (isset($this->items[$name])) {
            return $this->items[$name];
        }

        throw new Exception("Can not find menu with name $name");
    }

    /**
     * Get all registered item inside the container.
     *
     * @return array array of MenuItem object
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * Render menu item list based on registered MenuRenderer.
     *
     * @return string rendered item list
     */
    public function render()
    {
        if ($this->renderer) {
            return $this->renderer->render($this);
        }
    }

    /**
     * Set menu renderer to render menu into specific format.
     *
     * @param MenuRendererInterface $renderer The menu renderer class
     */
    public function setRenderer(MenuRendererInterface $renderer)
    {
        $this->renderer = $renderer;
    }
}
