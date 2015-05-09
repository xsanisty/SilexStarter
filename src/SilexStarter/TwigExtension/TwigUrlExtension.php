<?php

namespace SilexStarter\TwigExtension;

use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Twig_Extension;
use Twig_SimpleFunction;

class TwigUrlExtension extends Twig_Extension
{
    protected $urlGenerator;
    protected $requestStack;

    public function __construct(RequestStack $requestStack, UrlGenerator $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
        $this->requestStack = $requestStack;
    }

    public function getName()
    {
        return 'silex-starter-url-ext';
    }

    public function getFunctions()
    {
        return [
            new Twig_SimpleFunction('url_for', [$this, 'urlFor']),
            new Twig_SimpleFunction('url_to', [$this, 'urlTo']),
        ];
    }

    public function urlFor($route)
    {
        try {
            return $this->urlGenerator->generate($route);
        } catch (RouteNotFoundException $e) {
            return $this->urlTo($route);
        }
    }

    public function urlTo($path = '/')
    {
        $request = $this->requestStack->getCurrentRequest();
        return $request->getScheme().'://'.$request->getHost().'/'.ltrim($path, '/');
    }
}
