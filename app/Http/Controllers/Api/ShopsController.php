<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Shop;
use App\Transformers\ShopTransformer;
class ShopsController extends Controller
{
    public function index()
    {
    	$shops=Shop::where('is_show',1)->orderBy('sort')->get();
    	return $this->response->collection($shops,new ShopTransformer());

    }

    public function show(Request $request)
    {
    	$shop_id=$request->shop;
    	$shop=Shop::find($shop_id);
        $shop->banner=$this->format_banner($shop->banner);
    	return $this->response->array($shop);
    }

    private function format_banner($banner)
    {
       
        foreach ($banner as $k => $v) {
            $banner[$k]=env('APP_URL').'/uploads/'.$v;
        }

        return $banner;
    }
}
