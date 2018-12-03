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
        'limit' => config('api.rate_limits.sign.limit'),
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
	});

	// 刷新token
	$api->put('authorizations/current', 'AuthorizationsController@update')
    	->name('api.authorizations.update');
	// 删除token
	$api->delete('authorizations/current', 'AuthorizationsController@destroy')
    	->name('api.authorizations.destroy');

    
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

    	//登陆可访问
    	$api->group(['middleware' => 'api.auth'], function($api) {
    		//用户信息
    		$api->get('user','UsersController@show')
    			->name('api.user.show');
            //用户修改信息
            $api->patch('user','UsersController@update')
                ->name('api.user.update');
    	});
    });
    
});
