<?php

/** admin route that don't need session checkpoint */
Route::get('/admin/login', 'Admin\Controller\AdminController:login')
     ->bind('admin.login')
     ->before(App::filter('admin.guest'));

Route::post('/admin/login', 'Admin\Controller\AdminController:authenticate')
     ->bind('admin.authenticate');

Route::get('/admin/logout', 'Admin\Controller\AdminController:logout')
     ->bind('admin.logout');

Route::get('/admin', 'Admin\Controller\AdminController:index')
     ->bind('admin.home')
     ->before(App::filter('admin.auth'));


Route::group('/admin', function($route){
    $route->get('/something', 'Admin\Controller\AdminController:index');
})->before(App::filter('admin.auth'));

Route::group('level1', function($route){
    $route->get('level2', function(){
        return 'level 1.2';
    });
    $route->mount('level2', Route::group('/', function($route){
        $route->get('/level3', function(){
            return 'level 1.2.3';
        });
    }));

});
