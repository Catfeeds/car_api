<?php

namespace App\Transformers;

use App\Models\Classify;
use League\Fractal\TransformerAbstract;

class ClassifyTransformer extends TransformerAbstract
{
	public function transform(Classify $classify)
    {
        return [
            'id' => $classify->id,
            'title' => $classify->title,
            'image'=>env('APP_URL').'/uploads/'.$classify->image
        ];
    }
}