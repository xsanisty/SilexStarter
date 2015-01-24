<?php

namespace SilexStarter\Providers;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Silex\Provider\TwigCoreExtension;
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

            if ($app['config']['twig.options.debug']) {
                $twigEnv->addExtension(new \Twig_Extension_Debug());
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

                if (isset($app['form.factory'])) {
                    $app['twig.form.engine'] = $app->share(function ($app) {
                        return new TwigRendererEngine($app['twig.form.templates']);
                    });

                    $app['twig.form.renderer'] = $app->share(function ($app) {
                        return new TwigRenderer($app['twig.form.engine'], $app['form.csrf_provider']);
                    });

                    $twigEnv->addExtension(new FormExtension($app['twig.form.renderer']));

                    // add loader for Symfony built-in form templates
                    $reflected = new \ReflectionClass('Symfony\Bridge\Twig\Extension\FormExtension');
                    $path = dirname($reflected->getFileName()).'/../Resources/views/Form';
                    $app['twig.loader']->addLoader(new \Twig_Loader_Filesystem($path));
                }
            }

            return $twigEnv;

        });
    }

    public function boot(Application $app){

    }
}