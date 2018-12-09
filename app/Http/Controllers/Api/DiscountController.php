<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\ProductSku;
use App\Models\Product;
use App\Transformers\DiscountTransformer;
use App\Transformers\ProductItemTransformer;

class DiscountController extends Controller
{
    public function index(Request $request)
    {
        $builder=Product::where('is_sale',1)->whereHas('skus',function($query){
            return $query->where('is_discount',1);
        });
    	// $builder=ProductSku::where('is_discount',1)->where('is_sale',1);
        if($request->limit){
            $builder->limit($request->limit);
            $discounts=$builder->get();
            return $this->response->collection($discounts,new DiscountTransformer());
        }else{

            $discounts=$builder->paginate(4);
            return $this->response->paginator($discounts,new DiscountTransformer());
        }
        
    	
    	
    	
    }

    public function show(Request $request)
    {
    	$product=Product::find($request->sku_id);
        $product_skus=$product->skus()->where('is_discount',1)->get(['id','color','configuration','style','foreign_price','rate','price','discount_price'])->toArray();
        
        return $this->response->item($product,new ProductItemTransformer())
            ->setMeta($product_skus);
    }
}
