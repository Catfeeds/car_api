<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class shop extends Model
{
    public function setBannerAttribute($pictures)
	{
	    if (is_array($pictures)) {
	        $this->attributes['banner'] = json_encode($pictures);
	    }
	}

	public function getBannerAttribute($pictures)
	{
	    return json_decode($pictures, true);
	}
}
