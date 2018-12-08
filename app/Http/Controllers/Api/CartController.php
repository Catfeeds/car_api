<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests\Api\AddCartRequest;
use App\Transformers\CartTransformer;
use App\Models\CartItem;
use App\Models\ProductSku;
use App\Models\Order;
use App\Models\OrderItem;

class CartController extends Controller
{
    public function store(AddCartRequest $request)
    {
    	$user=$this->user();
    	$skuId  = $request->input('sku_id');
    	if($cart=$user->cartItems()->where('product_sku_id', $skuId)->first())
    	{
    		return $this->response->error('该商品已存在购物车',422);
    	}


        $new_cart=new CartItem(['loan_status'=>$request->loan_status]);
        $new_cart->user()->associate($user);
        $new_cart->productSku()->associate($skuId);
        $new_cart->save();

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

    public function cart_to_order(Request $request)
    {
        $user=$this->user();
        
        if(!$user->address || !$user->id_number || !$user->username)
        {
            return $this->response->error('请先完善个人信息',422);
        }

        $cart_id=$request->cart_id;
        $cart_item=CartItem::find($cart_id);

        $sku_id=$cart_item->product_sku_id;
        $sku  = ProductSku::find($sku_id);
        
        if(!$sku->is_sale){
            return $this->response->error('该商品已下架',422);
        }
        
        if($sku->is_discount){
            $total_amount=$sku->discount_price;
        }else{
            $total_amount=$sku->price;
        }

        $order=new Order([
            'address'=>$user->address,
            'total_amount' => $total_amount,
            'loan_status'=>$cart_item->loan_status
        ]);

        $order->user()->associate($user);
        $order->save();

        $sku_arr=[
            'sku_info'=>['id'=>$sku->id,'color'=>$sku->color,'configuration'=>$sku->configuration,'style'=>$sku->style],
            'product_info'=>[
                'title'=>$sku->product->title,
                'id'=>$sku->product->id,
                'image'=>$sku->product->image
            ]
        ];

        $order_item=new OrderItem(['product_content'=>json_encode($sku_arr)]);
        $order_item->order()->associate($order);
        $order_item->save();

        $cart_item->delete();

        return $this->response->array([
            'order_id'=>$order->id,
        ]);

    }
}
