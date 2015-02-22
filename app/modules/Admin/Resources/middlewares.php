<?php

/** put your module middlewares here */
App::filter('admin.auth', function(){
    if(!Sentry::check()){
        return Response::redirect(Url::to('admin.login'));
    }
});

App::filter('admin.guest', function(){
    if(Sentry::check()){
        return Response::redirect(Url::to('admin.home'));
    }
});