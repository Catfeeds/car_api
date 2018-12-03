<?php

use Illuminate\Routing\Router;

Admin::registerAuthRoutes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index');
    $router->resource('/banner',BannersController::class);
    $router->resource('/classify',ClassifiesController::class);
    $router->resource('/shop',ShopsController::class);
    $router->resource('/product',ProductsController::class);
    $router->resource('/user',UsersController::class);
});
