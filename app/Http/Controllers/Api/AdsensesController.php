<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Adsense;
use App\Transformers\AdsenseTransformer;

class AdsensesController extends Controller
{
    public function index()
    {
    	$adsenses=Adsense::where('is_show',1)->orderBy('sort')->get();
    	return $this->response->collection($adsenses,new AdsenseTransformer());
    }
}
