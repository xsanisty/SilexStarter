<?php

namespace SilexStarter\Providers;

use Silex\Application;
use Silex\ServiceProviderInterface;

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

            if ($app['config']['debug']) {
                $twigEnv->addExtension(new \Twig_Extension_Debug());
            }


        });
    }

    public function boot(Application $app){

    }
}