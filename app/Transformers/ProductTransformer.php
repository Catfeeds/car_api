<?php

namespace App\Transformers;

use App\Models\Product;
use League\Fractal\TransformerAbstract;

class ProductTransformer extends TransformerAbstract
{
	public function transform(Product $product)
    {
        return [
            'id' => $product->id,
            'title' => $product->title,
            'image'=>env('APP_URL').'/uploads/'.$product->image,
            'price'=>$product->price,
            'abstract'=>$product->abstract
        ];
    }
}