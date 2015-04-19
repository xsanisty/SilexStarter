<?php

namespace SilexStarter\TwigExtension;

use SilexStarter\Asset\AssetManager;
use Twig_Extension;
use Twig_SimpleFunction;

class TwigAssetExtension extends Twig_Extension
{
    protected $manager;

    public function __construct(AssetManager $manager)
    {
        $this->manager = $manager;
    }

    public function getName()
    {
        return 'silex-starter-asset-ext';
    }

    public function getFunctions()
    {
        return [
            new Twig_SimpleFunction('stylesheet', [$this, 'stylesheet'], ['is_safe' => ['html']]),
            new Twig_SimpleFunction('javascript', [$this, 'javascript'], ['is_safe' => ['html']]),
            new Twig_SimpleFunction('css', [$this, 'stylesheet'], ['is_safe' => ['html']]),
            new Twig_SimpleFunction('js', [$this, 'javascript'], ['is_safe' => ['html']]),
            new Twig_SimpleFunction('asset', [$this, 'asset']),
        ];
    }

    public function stylesheet($file = null)
    {
        return $this->manager->renderCss($file);
    }

    public function javascript($file = null)
    {
        return $this->manager->renderJs($file);
    }

    public function asset($file)
    {
        return $this->manager->resolvePath($file);
    }
}
