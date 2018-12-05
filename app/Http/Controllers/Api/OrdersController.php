<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductSku;
use App\Http\Requests\Api\OrderRequest;
use App\Transformers\OrderTransformer;

class OrdersController extends Controller
{
    public function store(OrderRequest $request)
    {
    	$user=$this->user();
    	
    	if(!$user->address || !$user->id_number || !$user->username)
    	{
    		return $this->response->error('请先完善个人信息',422);
    	}

    	$sku_id=$request->sku_id;
    	$sku  = ProductSku::find($sku_id);
    	
    	$order=new Order([
    		'address'=>$user->address,
    		'total_amount' => $sku->price,
    	]);

    	$order->user()->associate($user);
    	$order->save();

    	

    	
    	$sku_arr=[
    		'sku_info'=>['color'=>$sku->color,'configuration'=>$sku->configuration,'style'=>$sku->style,'price'=>$sku->price],
    		'product_info'=>[
    			'title'=>$sku->product->title
    		]
    	];

    	$order_item=new OrderItem(['product_content'=>json_encode($sku_arr)]);
    	$order_item->order()->associate($order);
		$order_item->save();

		return $this->response->created();

    }

    public function index()
    {
        $user=$this->user();
        return $this->response->collection($user->orders,new OrderTransformer());
    }

    public function show(Request $request)
    {
        $order=Order::find($request->order);
        $logistics=$order->logistics()->get(['content','created_at']);
        $info=json_decode($order->items()->first()->product_content);
        return $this->response->array(compact('order','logistics','info'));
    }
}
