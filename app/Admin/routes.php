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
    $router->resource('/set',SetsController::class);
    $router->resource('/order',OrdersController::class);

    //物流信息
    $router->resource('/logistics',LogisticsController::class);

    //贷款详情
    $router->get('/loan_show','LoanShowController@index');
});
