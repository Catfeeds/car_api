<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Classify;
use App\Transformers\ProductItemTransformer;
use App\Transformers\ProductTransformer;

class ProductsController extends Controller
{
    public function index(Request $request)
    {
    	$builder=Classify::find($request->classify_id)->products()->where('is_sale',1);
    	if($request->keywords){
    		$builder->where('keywords','like','%'.$request->keywords.'%');
    	}
        if($request->limit)
        {
            $builder->limit($request->limit);
        }

    	$products=$builder->orderBy('sort')->get();
    	return $this->response->collection($products,new ProductTransformer());
    }

    
    public function show(Request $request)
    {
    	$product=Product::find($request->product);
    	$product_skus=$product->skus()->get(['id','color','configuration','style','foreign_price','rate','price'])->toArray();
        
        return $this->response->item($product,new ProductItemTransformer())
            ->setMeta($product_skus);
    }

    
    public function hot(Request $request)
    {

    	$builder=Product::where(['is_sale'=>1,'is_hot'=>1])->orderBy('sort');
        if($request->limit){
            $builder->limit($request->limit);
        }
        $products=$builder->get();
    	return $this->response->collection($products,new ProductTransformer());
    }
}
