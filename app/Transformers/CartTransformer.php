<?php

namespace App\Transformers;

use App\Models\CartItem;
use League\Fractal\TransformerAbstract;

class CartTransformer extends TransformerAbstract
{
	public function transform(CartItem $cart)
    {
        return [
            'id' => $cart->id,
            'sku'=>$cart->productSku()->first(['id','color','configuration','style','price']),
            'product'=>$cart->productSku->product()->first(['id','title','image']),
            'loan_status'=>$cart->loan_status
        ];
    }
}