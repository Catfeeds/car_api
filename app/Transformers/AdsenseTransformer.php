<?php

namespace App\Transformers;

use App\Models\Adsense;
use League\Fractal\TransformerAbstract;

class AdsenseTransformer extends TransformerAbstract
{
	public function transform(Adsense $adsense)
    {
        return [
            'id' => $adsense->id,
            'title' => $adsense->title,
            'image'=>env('APP_URL').'/uploads/'.$adsense->image
        ];
    }
}