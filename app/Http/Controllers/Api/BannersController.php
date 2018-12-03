<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Banner;
use App\Transformers\BannerTransformer;

class BannersController extends Controller
{
    public function index()
    {
    	$banners=Banner::where('is_show',1)->orderBy('sort')->get();
    	return $this->response->collection($banners,new BannerTransformer());
    }
}
