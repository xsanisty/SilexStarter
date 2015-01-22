<?php

Route::get('/', 'SomeNamespace\OtherController:index');

/** Test group route */
Route::group('group', function($route){
    $route->get('/', 'GroupController:index');
    $route->get('/list', 'GroupController:lists');
    $route->get('/test', 'GroupController:test');
});

/** Test resource route */
Route::resource('books', 'BookController');

/** Test controller route */
Route::controller('auto', 'AutoRouteController');