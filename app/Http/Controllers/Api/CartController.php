<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests\Api\AddCartRequest;
use App\Transformers\CartTransformer;
use App\Models\CartItem;

class CartController extends Controller
{
    public function store(AddCartRequest $request)
    {
    	$user=$this->user();
    	$skuId  = $request->input('sku_id');
    	if($cart=$user->cartItems('product_sku_id', $skuId)->first())
    	{
    		return $this->response->error('该商品已存在购物车',422);
    	}


        $cart=new CartItem();
        $cart->user()->associate($user);
        $cart->productSku()->associate($skuId);
        $cart->save();

    }

    public function index()
    {
    	$user=$this->user();
    	$cart=$user->cartItems()->with(['productSku.product'])->get();

    	return $this->response->collection($cart,new CartTransformer);
    }

    public function destory(Request $request)
    {
    	$cartitem=CartItem::find($request->cart_id);
    	$cartitem->delete();
    }
}
