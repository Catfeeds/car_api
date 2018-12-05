<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\ProductSku;
use App\Transformers\DiscountTransformer;
use App\Transformers\ProductItemTransformer;

class DiscountController extends Controller
{
    public function index()
    {
    	$discounts=ProductSku::where('is_discount',1)->get();
    	
    	return $this->response->collection($discounts,new DiscountTransformer());
    	
    }

    public function show(Request $request)
    {
    	$sku=ProductSku::find($request->sku_id);
    	$product=$sku->product;
    	
    	$product_skus=ProductSku::where('id',$request->sku_id)->get(['id','color','configuration','style','price','discount_price'])->toArray();
    	
    	return $this->response->item($product,new ProductItemTransformer())
            ->setMeta($product_skus);
    }
}
