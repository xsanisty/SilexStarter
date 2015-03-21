<?php

namespace SileStarter\Menu;

interface MenuRendererInterface
{
    /**
     * Set the menu collection set
     * @param MenuContainer $menu
     */
    public function setMenu(MenuContainer $menu);

    /**
     * Render the menu collection set
     * @return string
     */
    public function render();
}