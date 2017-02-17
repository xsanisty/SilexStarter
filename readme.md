[![Build Status](https://scrutinizer-ci.com/g/xsanisty/SilexStarter/badges/build.png?b=develop)]
(https://scrutinizer-ci.com/g/xsanisty/SilexStarter/build-status/develop)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/xsanisty/SilexStarter/badges/quality-score.png?b=develop)]
(https://scrutinizer-ci.com/g/xsanisty/SilexStarter/?branch=develop)
[![Code Coverage](https://scrutinizer-ci.com/g/xsanisty/SilexStarter/badges/coverage.png?b=develop)]
(https://scrutinizer-ci.com/g/xsanisty/SilexStarter/?branch=develop)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/a30a66f9-8110-40c5-8a35-f3c1697dde55/mini.png)]
(https://insight.sensiolabs.com/projects/a30a66f9-8110-40c5-8a35-f3c1697dde55)

![screenshot](https://github.com/xsanisty/SilexStarter/blob/develop/screenshot.png)


# SilexStarter

SilexStarter is a starter application built on the top of Silex framework, Laravel's Eloquent, Cartalyst Sentry,
and some other third party and built in components.
SilexStarter aim to help building simple application faster, it built with MVC and modular approach in mind,
and comes with some basic admin module, including user manager, and module manager.

## Installation
For now, the installable branch is only develop branch, you can easily install it using composer by using following command

```
composer create-project xsanisty/silexstarter target_dir dev-develop -s dev
```

once composer install is completed, you can initialize the app using following command
```
$cd target_dir
$./xpress app:init
```

Module can be developed directly inside the ```app/modules``` directory and create module on its own namespace
or build it as separate composer package, module can be enabled or disabled by registering it in ```app/config/modules.php```

## Route
```file : app/routes.php```
Route configuration can be created like normal Silex route, or using route builder using similar syntax like Laravel's route.

Silex routing style
```php
/* the application instance is available as $app in context of route.php */

$app->get('/page', function(){
    return 'I am in a page';
});

$app->post('/save', function(){
   return 'Ok, all data is saved!';
});

/* grouping */
$app->group('prefix', function() use ($app) {
    $app->group('page', function() use ($app) {
        $app->get('index', function(){
            return 'I am in prefix/page/index';
        });
    });
});

/* resourceful controller */
$app->resource('prefix', 'SomeController');

/* route controller */
$app->controller('prefix', 'SomeController');

```

Laravel routing style
```php
/* route can be built using the Route static proxy */

Route::get('/page', function(){
    return 'I am in a page';
});

Route::post('/save', function(){
   return 'Ok, all data is saved!';
});

/* grouping */
Route::group('prefix', function() {
    Route::group('page', function() {
        Route::get('index', function(){
            return 'I am in prefix/page/index';
        });
    });
});

/* resourceful controller */
Route::resource('prefix', 'SomeController');

/* route controller */
Route::controller('prefix', 'SomeController');
```

## Controller
```file: app/controllers/*```

Controller basically can be any classes that reside in controllers folder, it will be registered as service
when enabled, and will be properly instantiated when needed, with all dependency injected.

Assume we have ```PostRepository``` and ```CommentRepository```, we should register it first before it can be
properly injected into controller.

```file: app/services/RepositoryServiceProvider.php```

```php
<?php

use Silex\Application;
use Silex\ServiceProviderInterface;

class RepositoryServiceProvider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app['PostRepository'] = $app->share(function (Application $app) {
            return new PostRepository
        });

        $app['CommentRepository'] = $app->share(function (Application $app) {
            return new PostRepository
        });
    }

    public function boot(Application $app)
    {

    }
}
```

```file: app/config/services.php```
```php
<?php

return [
    'common' => [
        'RepositoryServiceProvider',
    ]
]
```

```file: app/controllers/PostController.php```
```php
<?php

class PostController{

    protected $postRepo;
    protected $commentRepo;

    public function __construct(PostRepository $postRepo, CommentRepository $commentRepo)
    {
        $this->postRepo = $postRepo;
        $this->commentRepo = $commentRepo;
    }

    public function index(){
        return Response::view('post/index', $this->postRepo->all());
    }
}
```

and now, we should able to create route map to this controller

```php
Route::get('/post', 'PostController:index');
```

## Model
```file: app/models/*```

SilexStarter use Eloquent ORM as database abstraction layer, so you can extends it to create model classJeyac.
The configuration of the database can be found in ```app/config/database.php```

```php
<?php

class Post extends Model{
    protected $table = 'posts';

    public function comments(){
        return $this->hasMany('Comment');
    }
}
```

## View
```file: app/views/*```

## Middleware
```file: app/middlewares.php```

## Service Provider
```file: src/Providers/*```

## Module
```file: app/modules/*```

### Register module
```file: app/config/modules.php```

### Module Provider
```file: app/modules/**/ModuleProvider.php```

## Menu

## Asset
