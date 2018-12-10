<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductSku extends Model
{
	protected $fillable=['color','configuration','style','foreign_price','rate','price','is_sale','product_id','is_discount','discount_price'];
    

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    

    
}
