<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductSku;
use App\Models\Set;
use App\Http\Requests\Api\OrderRequest;
use App\Transformers\OrderTransformer;

class OrdersController extends Controller
{
    public function store(OrderRequest $request)
    {
    	$user=$this->user();
    	
    	if(!$user->address || !$user->id_number || !$user->username)
    	{
    		return $this->response->error('请先完善个人信息',403);
    	}

    	$sku_id=$request->sku_id;
    	$sku  = ProductSku::find($sku_id);
        
        if($sku->is_discount){
            $total_amount=$sku->discount_price;
        }else{
            $total_amount=$sku->price;
        }
       
    	
    	$order=new Order([
    		'address'=>$user->address,
    		'total_amount' => $total_amount,
            'loan_status'=>$request->loan_status
    	]);

    	$order->user()->associate($user);
    	$order->save();

    	

    	
    	$sku_arr=[
    		'sku_info'=>['id'=>$sku->id,'color'=>$sku->color,'configuration'=>$sku->configuration,'style'=>$sku->style],
    		'product_info'=>[
    			'title'=>$sku->product->title,
                'id'=>$sku->product->id,
                'image'=>env('APP_URL').'/uploads/'.$sku->product->image
    		]
    	];

    	$order_item=new OrderItem(['product_content'=>json_encode($sku_arr)]);
    	$order_item->order()->associate($order);
		$order_item->save();

        

		return $this->response->array([
            'order_id'=>$order->id,
        ]);

    }

    public function index()
    {
        $user=$this->user();
        return $this->response->collection($user->orders()->orderBy('created_at','desc')->get(),new OrderTransformer());
    }

    public function show(Request $request)
    {
        $order=Order::find($request->order);
        $logistics=$order->logistics()->get(['content','created_at']);
        $info=json_decode($order->items()->first()->product_content);

        $set=Set::find(1);
        if($order->loan_status){
            $account=[
                'account'=>$set->account,
                'phone'=>$set->loan_phone,
            ];
            
        }else{
            $account=[
                'account'=>$set->account,
                'phone'=>$set->no_loan_phone,
            ];
        }
        
        return $this->response->array(compact('order','logistics','info','account'));
    }
}
