<?php

namespace SilexStarter\Menu;

interface MenuRendererInterface
{
    /**
     * Render the menu collection set.
     *
     * @param SilexStarter\Menu\MenuContainer $menu the menu collection set
     *
     * @return string
     */
    public function render(MenuContainer $menu);
}
