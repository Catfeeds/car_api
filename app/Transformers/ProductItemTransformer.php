<?php

namespace App\Transformers;

use App\Models\Product;
use League\Fractal\TransformerAbstract;

class ProductItemTransformer extends TransformerAbstract
{
	public function transform(Product $product)
    {
        return [
            'id' => $product->id,
            'title' => $product->title,
            'banner'=>$product->banner,
            'description'=>$product->description,
            'abstract'=>$product->abstract
        ];
    }
}