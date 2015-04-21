<?php

namespace SilexStarter\Menu;

use Exception;

class MenuContainer
{
    /**
     * List of menu item.
     *
     * @var SilexStarter\Menu\MenuItem
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
     * [__construct description].
     *
     * @param [type] $name [description]
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * [getLevel description].
     *
     * @return [type] [description]
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * [setLevel description].
     *
     * @param [type] $level [description]
     */
    public function setLevel($level)
    {
        $this->level = $level;
        foreach ($this->items as $item) {
            $item->setLevel($level);
        }
    }

    /**
     * [createItem description].
     *
     * @param [type] $name       [description]
     * @param array  $attributes [description]
     *
     * @return [type] [description]
     */
    public function createItem($name, array $attributes)
    {
        $attributes['name'] = $name;
        $this->items[$name] = new MenuItem($attributes);

        return $this->items[$name];
    }

    /**
     * [addItem description].
     *
     * @param MenuItem $menu [description]
     */
    public function addItem(MenuItem $menu)
    {
        $this->items[$menu->getName()] = $menu;
    }

    /**
     * [removeItem description].
     *
     * @param [type] $name [description]
     *
     * @return [type] [description]
     */
    public function removeItem($name)
    {
        if (isset($this->items[$name])) {
            unset($this->items[$name]);
        }
    }

    /**
     * [setActive description].
     *
     * @param [type] $name [description]
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
     * [getItem description].
     *
     * @param [type] $name [description]
     *
     * @return [type] [description]
     */
    public function getItem($name)
    {
        if (isset($this->items[$name])) {
            return $this->items[$name];
        }

        throw new Exception("Can not find menu with name $name", 1);
    }

    /**
     * [getItems description].
     *
     * @return [type] [description]
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * [render description].
     *
     * @return [type] [description]
     */
    public function render()
    {
        if (!is_null($this->renderer)) {
            return $this->renderer->render($this);
        }
    }

    /**
     * [setRenderer description].
     *
     * @param MenuRendererInterface $renderer [description]
     */
    public function setRenderer(MenuRendererInterface $renderer)
    {
        $this->renderer = $renderer;
    }
}
