<?php

namespace SilexStarter\TwigExtension;

use SilexStarter\Menu\MenuManager;
use Twig_Extension;
use Twig_SimpleFunction;

class TwigMenuExtension extends Twig_Extension
{
    protected $menu;

    public function __construct(MenuManager $menu)
    {
        $this->menu = $menu;
    }

    public function getName()
    {
        return 'silex-starter-menu-ext';
    }

    public function getFunctions()
    {
        return [
            new Twig_SimpleFunction('render_menu', [$this, 'renderMenu'], ['is_safe' => ['html']]),
        ];
    }

    public function renderMenu($menu, array $option = [])
    {
        return $this->menu->render($menu, $option);
    }
}
