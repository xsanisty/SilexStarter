<?php

/**
 * You can place application route here.
 *
 * Sample general route
 *
 * Route::get('/', 'SomeNamespace\OtherController:index', $options);
 *
 * Sample group route
 *
 * Route::group('group', function(){
 *     Route::get('/', 'GroupController:index');       //accessible via domain.tld/group/ (with the trailing slash)
 *     Route::get('/list', 'GroupController:lists');   //accessible via domain.tld/group/list
 *     Route::get('/test', 'GroupController:test');    //accessible via domain.tld/group/test
 * });
 *
 * Sample resource route
 *
 * Route::resource('book', 'BookController');           //Similar to laravel's Route::resource, this will map
 *                                                      //GET    /book/         => BookController::index
 *                                                      //GET    /book/id       => BookController::show($id)
 *                                                      //GET    /book/id/edit  => BookController::edit($id)
 *                                                      //GET    /book/create   => BookController::create
 *                                                      //POST   /book/         => BookController::store
 *                                                      //PUT    /book/id       => BookController::update($id)
 *                                                      //DELETE /book/id       => BookController::delete($id)
 *
 * Sample controller route
 *
 * Route::controller('auto', 'AutoRouteController');    //method someTest will be accessible via ANY_METHOD /auto/some_test
 *                                                      //method getIndex will be accessible via GET /auto/ (with or without the trailing slash)
 *                                                      //method getSomePath will be accessible via GET /auto/some_path
 *                                                      //method deleteResource will be accessible via DELETE /auto/resource
 *                                                      //method postComment willbe accessible via POST /auto/comment
 *
 * Route options
 *
 *[
 *  'as'        => 'route.name',                //Give name to the route, so you can generate url from it, not applicable to Route::group
 *  'namespace' => 'Some\NameSpace',            //Namespace prefix without trailing leading slash, no need to write fqcn controller
 *  'permission'=> ['user.write', 'other'],     //Route permission, so only user has any listed permission could access the path
 *  'before'    => $callback,                   //Route before middleware, executed before the actual controller is executed
 *  'after'     => $callback,                   //Route after middleware, executed after actual controller is executed
 *  'assert'    => ['id' => '\d+'],             //Route variable assertion, not applicable for Route::resource, Route::controller, Route::group
 *  'convert'   => ['id' => $convertCallback],  //Route variable converter, not applicable for Route::resource, Route::controller, Route::group
 *  'default'   => ['id' => 1],                 //Route default variable, not applicable for Route::resource, Route::controller, Route::group
 *]
 */
