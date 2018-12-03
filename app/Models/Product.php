<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable=[
    	'title', 'description', 'image', 'on_sale', 'banner','sort','keywords','is_hot','classify_id'
    ];

    protected $casts = [
        'on_sale' => 'boolean', // on_sale 是一个布尔类型的字段
    ];


    public function skus()
    {
        return $this->hasMany(ProductSku::class);
    }

    public function classify()
    {
        return $this->belongsTo(classify::class);
    }

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
