<?php

/**
 * You can place application route here
 *
 * Sample general route
 * Route::get('/', 'SomeNamespace\OtherController:index');
 *
 * Sample group route
 * Route::group('group', function(){
 *     Route::get('/', 'GroupController:index');       //accessible via domain.tld/group/ (with the trailing slash)
 *     Route::get('/list', 'GroupController:lists');   //accessible via domain.tld/group/list
 *     Route::get('/test', 'GroupController:test');    //accessible via domain.tld/group/test
 * });
 *
 * Sample resource route
 * Route::resource('book', 'BookController');           //Similar to laravel's Route::resource, this will map
 *                                                      //GET    /book/         => BookController::index
 *                                                      //GET    /book/id       => BookController::show($id)
 *                                                      //GET    /book/id/edit  => BookController::edit($id)
 *                                                      //GET    /book/create   => BookController::create
 *                                                      //POST   /book/         => BookController::store
 *                                                      //PUT    /book/id       => BookController::update($id)
 *                                                      //DELETE /book/id       => BookController::destroy($id)
 *
 * Sample controller route
 * Route::controller('auto', 'AutoRouteController');    //method someTest will be accessible via ANY_METHOD /auto/some_test
 *                                                      //method getIndex will be accessible via GET /auto/ (with or without the trailing slash)
 *                                                      //method getSomePath will be accessible via GET /auto/some_path
 *                                                      //method deleteResource will be accessible via DELETE /auto/resource
 *                                                      //method postComment willbe accessible via POST /auto/comment
 */

Route::get('/', 'MainController:index');

Route::group('/group1', function(){

    Route::group('/group2', function(){

        Route::group('/group3', function(){

            Route::get('/', function(){
                return 'test';
            });

            Route::controller('/controller', 'TestController' );
            Route::get('anotherTest', 'TestController::getTest');
            Route::resource('/resource', 'ResourceController');

        }, [
        'before' => function(){
            echo 'group3 middleware <br>';
        },
        'after' => function(){
            echo 'group3 after middleware <br>';
        }]);

    }, [
    'before' => function(){
        echo 'group2 middleware <br>';
    },
    'after' => function(){
        echo 'group2 after middleware <br>';
    }]);

}, [
'before' => function(){
    echo 'group1 middleware <br>';
},
'after' => function(){
    echo 'group1 after middleware <br>';
}]);