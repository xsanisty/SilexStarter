<?php

namespace SilexStarter\Menu;

class MenuManager
{
    protected $menuContainers = [];

    /**
     * [create description].
     *
     * @param [type] $name [description]
     *
     * @return [type] [description]
     */
    public function create($name)
    {
        $this->menuContainers[$name] = new MenuContainer($name);

        return $this->menuContainers[$name];
    }

    /**
     * [get description].
     *
     * @param [type] $name [description]
     *
     * @return [type] [description]
     */
    public function get($name)
    {
        return $this->menuContainers[$name];
    }

    /**
     * [render description].
     *
     * @param [type] $name [description]
     *
     * @return [type] [description]
     */
    public function render($name)
    {
        return $this->menuContainers[$name]->render();
    }
}
