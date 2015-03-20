<?php

namespace SilexStarter\Provider;

use Twig_Extension_Debug;
use Silex\Application;
use Silex\ServiceProviderInterface;
use Silex\Provider\TwigCoreExtension;
use SilexStarter\TwigExtension\TwigAssetExtension;
use SilexStarter\TwigExtension\TwigMenuExtension;
use Symfony\Bridge\Twig\Extension\RoutingExtension;
use Symfony\Bridge\Twig\Extension\TranslationExtension;
use Symfony\Bridge\Twig\Extension\FormExtension;
use Symfony\Bridge\Twig\Extension\SecurityExtension;
use Symfony\Bridge\Twig\Extension\HttpKernelExtension;
use Symfony\Bridge\Twig\Form\TwigRendererEngine;
use Symfony\Bridge\Twig\Form\TwigRenderer;

class TwigServiceProvider implements ServiceProviderInterface{

    public function register(Application $app){

        $app['twig.loader.filesystem'] = $app->share(function(Application $app){
            return new \Twig_Loader_Filesystem($app['config']['twig.template_dir']);
        });

        $app['twig.loader'] = $app->share(function(Application $app){
            return $app['twig.loader.filesystem'];
        });

        $app['twig'] = $app->share(function(Application $app){
            $app['config']['twig.options'] = array_replace(
                array(
                    'charset'          => $app['charset'],
                    'debug'            => $app['debug'],
                    'strict_variables' => $app['debug'],
                ), $app['config']['twig.options']
            );

            $twigEnv = new \Twig_Environment(
                $app['twig.loader'],
                $app['config']['twig.options']
            );

            $twigEnv->addExtension(new TwigAssetExtension($app['asset_manager']));
            $twigEnv->addExtension(new TwigMenuExtension($app['menu_manager']));
            $twigEnv->addGlobal('config', $app['config']);

            if ($app['config']['twig.options.debug']) {
                $twigEnv->addExtension(new Twig_Extension_Debug());
            }

            if($app['enable_profiler']){
                $twigEnv->addGlobal('app', $app);
            }

            if (class_exists('Symfony\Bridge\Twig\Extension\RoutingExtension')) {
                if (isset($app['url_generator'])) {
                    $twigEnv->addExtension(new RoutingExtension($app['url_generator']));
                }

                if (isset($app['translator'])) {
                    $twigEnv->addExtension(new TranslationExtension($app['translator']));
                }

                if (isset($app['security'])) {
                    $twigEnv->addExtension(new SecurityExtension($app['security']));
                }

                if (isset($app['fragment.handler'])) {
                    $app['fragment.renderer.hinclude']->setTemplating($twig);

                    $twigEnv->addExtension(new HttpKernelExtension($app['fragment.handler']));
                } else {
                    // fallback for BC, to be removed in 1.3
                    $twigEnv->addExtension(new TwigCoreExtension());
                }
            }

            return $twigEnv;

        });
    }

    public function boot(Application $app){

    }
}