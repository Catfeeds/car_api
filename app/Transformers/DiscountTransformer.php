<?php

namespace App\Transformers;

use App\Models\ProductSku;
use League\Fractal\TransformerAbstract;

class DiscountTransformer extends TransformerAbstract
{
	public function transform(ProductSku $sku)
    {
        return [
            'id' => $sku->id,
            'title'=>$sku->product->title,
            'image'=>$sku->product->image,
            'color'=>$sku->color,
            'configuration'=>$sku->configuration,
            'style'=>$sku->style,
            'price'=>$sku->price,
            'discount_price'=>$sku->discount_price,
        ];
    }
}