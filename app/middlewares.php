<?php

/**
 * You can place the application middleware
 *
 * App::before(function(Request $req, Application $app){
 *     //do your action here
 * });
 *
 * App::after(function(Request $req, Response $resp){
 *     //do your action here
 * });
 *
 * or registering common middleware to be used in route
 *
 * App::middleware('middleware.name', function(Request $req, Response $resp, Application $app){
 *     //do your action here
 * });
 *
 * and use it later as your route middleware
 *
 * Route::get('/somewere', 'SomeNamespace\SomeController:someAction')
 *      ->before(App::middleware('middleware.name'));
 *
 * you can use App::filter as alias of App::middleware, you can register the middleware via
 *
 * App::filter('name', $callback) or App::middleware('name', $callback)
 */
