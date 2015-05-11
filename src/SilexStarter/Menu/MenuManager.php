<?php

namespace SilexStarter\Menu;

class MenuManager
{
    /**
     * A list of MenuContainer object.
     *
     * @var array of SilexStarter\Menu\MenuContainer
     */
    protected $menuContainers = [];

    /**
     * Create new MenuContainer object and assign to menu container lists.
     *
     * @param string $name MenuContainer name
     *
     * @return SilexStarter\Menu\MenuContainer
     */
    public function create($name)
    {
        $this->menuContainers[$name] = new MenuContainer($name);

        return $this->menuContainers[$name];
    }

    /**
     * Get MenuContainer object based on it's name.
     *
     * @param string $name MenuContainer name
     *
     * @return SilexStarter\Menu\MenuContainer
     */
    public function get($name)
    {
        return $this->menuContainers[$name];
    }

    /**
     * Render specified MenuContainer.
     *
     * @param string $name MenuContainer name
     *
     * @return string
     */
    public function render($name)
    {
        return $this->menuContainers[$name]->render();
    }
}
