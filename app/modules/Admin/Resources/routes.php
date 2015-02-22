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


Route::group('/admin', function(){
    Route::get('/something', 'Admin\Controller\AdminController:index');
})->before(App::filter('admin.auth'));
