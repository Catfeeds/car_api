<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Shop;
use App\Transformers\ShopTransformer;
class ShopsController extends Controller
{
    public function index()
    {
    	$shops=Shop::where('is_show',1)->orderBy('sort')->get();
    	return $this->response->collection($shops,new ShopTransformer());

    }

    public function show(Request $request)
    {
    	$shop_id=$request->shop;
    	$shop=Shop::find($shop_id);
    	return $this->response->array($shop);
    }
}
