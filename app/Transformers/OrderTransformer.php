<?php

namespace App\Transformers;

use App\Models\Order;
use League\Fractal\TransformerAbstract;

class OrderTransformer extends TransformerAbstract
{
	public function transform(Order $order)
    {
        return [
            'id' => $order->id,
            'no'=>$order->no,
            'price'=>$order->total_amount,
            'info'=>json_decode($order->items()->first()->product_content),
            'intention_money'=>$order->intention_money,
            'pay_status'=>$order->pay_status,
            'loan_status'=>$order->loan_status,
            'created_at'=>date('Y-m-d',strtotime($order->created_at)),
        ];
    }
}