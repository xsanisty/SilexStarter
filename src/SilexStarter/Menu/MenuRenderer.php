<?php

namespace SilexStarter\Menu;

use SilexStarter\Contracts\MenuRendererInterface;

class MenuRenderer implements MenuRendererInterface
{
    protected $menu;

    public function render(MenuContainer $menu)
    {
        return $this->createHtml($menu);
    }

    protected function createHtml(MenuContainer $menu)
    {
        $format = '<li class="%s" id="%s"><a href="%s">%s  %s</a> %s </li>';
        $html   = ($menu->getLevel() == 0) ? '<ul class="sidebar">' : '<ul>';
        foreach ($menu->getItems() as $item) {
            $html .= sprintf(
                $format,
                $item->getAttribute('class'),
                $item->getAttribute('id'),
                $item->getAttribute('url'),
                $item->getAttribute('label'),
                ($item->getAttribute('icon')) ? '<span class="menu-icon glyphicon glyphicon-'.$item->getAttribute('icon').'"></span>' : '',
                $this->createHtml($item->getChildren())
            );
        }
        $html .= '</ul>';

        return $html;
    }
}
