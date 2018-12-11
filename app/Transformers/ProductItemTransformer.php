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
            'banner'=>$this->format_banner($product->banner),
            'description'=>$product->description,
            'abstract'=>$product->abstract
        ];
    }

    private function format_banner($banner)
    {
       
        foreach ($banner as $k => $v) {
            $banner[$k]=env('APP_URL').'/uploads/'.$v;
        }

        return $banner;
    }
}