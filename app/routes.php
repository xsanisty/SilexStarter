<?php

/**
 * You can place application route here
 *
 * Sample general route
 * Route::get('/', 'SomeNamespace\OtherController:index');
 *
 * Sample group route
 * Route::group('group', function($route){
 *     $route->get('/', 'GroupController:index');       //accessible via domain.tld/group/ (with the trailing slash)
 *     $route->get('/list', 'GroupController:lists');   //accessible via domain.tld/group/list
 *     $route->get('/test', 'GroupController:test');    //accessible via domain.tld/group/test
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
 * Route::controller('auto', 'AutoRouteController');    //method getIndex() will be accessible via /auto/ (with the trailing slash)
 *                                                      //method getSomePath will be accessible via /auto/some_path
 *                                                      //method deleteResource will be accessible via DELETE /auto/resource
 */

Route::group('/group', function(){
    Route::get('/test', function(){
        return Response::make('group/test');
    });

    Route::group('/sub', function(){
        Route::get('/level1', function(){
            return Response::make('group/sub/level1');
        });
        Route::get('/level2', function(){
            return Response::make('group/sub/level2');
        });
        Route::get('/level3', function(){
            return Response::make('group/sub/level3');
        });
        Route::get('/level4', function(){
            return Response::make('group/sub/level4');
        });
    })
    ->before(function(){
        return Response::make('sub1 middleware');
    });

    Route::group('/sub2', function(){
        Route::get('/level1', function(){
            return Response::make('group/sub2/level1');
        });
        Route::get('/level2', function(){
            return Response::make('group/sub2/level2');
        });
        Route::get('/level3', function(){
            return Response::make('group/sub2/level3');
        });
        Route::get('/level4', function(){
            return Response::make('group/sub2/level4');
        });
    })
    ->before(function(){
        return Response::make('sub2 middleware');
    });
})
->before(function(){
    return Response::make('group middleware');
});

Route::get('hello', function(){
    return 'hello';
});

Route::group('/groupA', function(){
    Route::group('/test', function(){
        Route::group('/leaf', function(){
            Route::get('/next', function(){
                return 'groupA/test/leaf/next';
            });
        });
    });
});

Route::group('/groupB', function(){
    Route::group('/test', function(){
        Route::group('/leaf', function(){
            Route::get('/next', function(){
                return 'groupB/test/leaf/next';
            });
        });
    });
});