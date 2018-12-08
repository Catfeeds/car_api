<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
$api = app('Dingo\Api\Routing\Router');

$api->version('v1', [
    'namespace' => 'App\Http\Controllers\Api',
    'middleware' => 'serializer:array'
], function($api) {
	$api->group([
		'middleware' => 'api.throttle',
        'limit' => 100,
        'expires' => config('api.rate_limits.sign.expires'),
	],function($api){
		// 短信验证码
    	$api->post('verificationCodes', 'VerificationCodesController@store')
        	->name('api.verificationCodes.store');

	    // 用户注册
		$api->post('users', 'UsersController@store')
	    	->name('api.users.store');

	    // 登录 获取token
	    $api->post('authorizations', 'AuthorizationsController@store')
    		->name('api.authorizations.store');
        //忘记密码验证码
        $api->post('forget_verificationCodes','VerificationCodesController@forget_store');
        //忘记密码
        $api->post('forget','UsersController@forget')
            ->name('api.users.forget');
	});

	// 刷新token
	$api->put('authorizations/current', 'AuthorizationsController@update')
    	->name('api.authorizations.update');
	// 删除token
	$api->delete('authorizations/current', 'AuthorizationsController@destroy')
    	->name('api.authorizations.destroy');

    $api->any('upload','UploadController@store');
    
    $api->group([
        'middleware' => 'api.throttle',
        'limit' => config('api.rate_limits.access.limit'),
        'expires' => config('api.rate_limits.access.expires'),
    ], function ($api) {
    	//游客可访问接口
    	//导航栏
    	$api->get('classifies','ClassifiesController@index')
    		->name('api.classifies.index');
    	//轮播图
    	$api->get('banners','BannersController@index')
    		->name('api.banners.index');
    	//门店列表
    	$api->get('shops','ShopsController@index')
    		->name('api.shops.index');
    	$api->get('shops/{shop}','ShopsController@show')
    		->name('api.shops.show');
        //商品列表
        $api->get('products','ProductsController@index')
            ->name('api.products.index');
        $api->get('products/{product}','ProductsController@show')
            ->name('api.products.show');
        //热门商品
        $api->get('hot','ProductsController@hot')
            ->name('api.products.hot');
        //折扣商品
        $api->get('discounts','DiscountController@index')
            ->name('api.discounts.index');
        //折扣商品详情
        $api->get('discounts/{sku_id}','DiscountController@show')
            ->name('api.discounts.show');
        

    	//登陆可访问
    	$api->group(['middleware' => 'api.auth'], function($api) {
    		//用户信息
    		$api->get('user','UsersController@show')
    			->name('api.user.show');
            //用户修改信息
            $api->PUT('user_info','UsersController@update')
                ->name('api.user.update');
            //购物车列表
            $api->get('carts','CartController@index')
                ->name('api.carts.index');
            //加入购物车
            $api->post('carts','CartController@store')
                ->name('api.carts.store');
            //删除购物车数据
            $api->delete('carts','CartController@destory')
                ->name('api.carts.destory');
            //下订单
            $api->post('orders','OrdersController@store')
                ->name('api.carts.store');
            //订单列表
            $api->get('orders','OrdersController@index')
                ->name('api.carts.store');
            //订单详情
            $api->get('orders/{order}','OrdersController@show')
                ->name('api.carts.show');
            //购物车下单
            $api->post('cart_to_order','CartController@cart_to_order')
                ->name('api.cart_to_order');
    	});
    });
    
});
