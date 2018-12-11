<?php

namespace App\Transformers;

use App\Models\CartItem;
use League\Fractal\TransformerAbstract;

class CartTransformer extends TransformerAbstract
{
	public function transform(CartItem $cart)
    {
    	$product=$cart->productSku->product()->first(['id','title','image']);
        return [
            'id' => $cart->id,
            'sku'=>$cart->productSku()->first(['id','color','configuration','style','price']),
            'product'=>[
            	'id'=>$product->id,
            	'title'=>$product->title,
            	'image'=>env('APP_URL').'/uploads/'.$product->image
            ],
            'loan_status'=>$cart->loan_status
        ];
    }
}