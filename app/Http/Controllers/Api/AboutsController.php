<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Set;

class AboutsController extends Controller
{
    public function index()
    {
    	$info=Set::find(1);
    	return $this->response->array([
    		'phone'=>$info->all_customer_phone,
    		'email'=>$info->email
    	]);
    }

    public function contact()
    {
    	$info=Set::find(1);
    	return $this->response->array([
    		'phone'=>$info->all_customer_phone,
    		'qq_image'=>env('APP_URL').'/uploads/'.$info->qq_image,
    		'wx_image'=>env('APP_URL').'/uploads/'.$info->wx_image,
    	]);
    }
}
