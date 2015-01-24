<?php

/**
 * You can place the application middleware here, by registering it to the $app object
 * or using Facade via App
 *
 * $app->before(function(Request $req, Application $app){
 *     //do your action here
 * });
 *
 * App::before(function(Request $req, Application $app){
 *     //do your action here
 * });
 */

App::filter('user.auth', function(){
    dd('I am filter');
});