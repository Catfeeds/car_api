<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Transformers\ClassifyTransformer;
use App\Models\Classify;

class ClassifiesController extends Controller
{
    public function index()
    {
    	$classifies=Classify::where('is_show',1)->orderBy('sort')->get();
    	return $this->response->collection($classifies,new ClassifyTransformer());
    }
}
