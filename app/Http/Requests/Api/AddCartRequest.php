<?php

namespace App\Http\Requests\Api;

use App\Models\ProductSku;

class AddCartRequest extends Request
{
    
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'sku_id'=>[
                'required',
                function($attribute,$value,$fail){
                    if(!$sku=ProductSku::find($value)){
                        $fail('该商品不存在');
                        return;
                    }
                    if (!$sku->product->is_sale) {
                        $fail('该商品未上架');
                        return;
                    }
                }
            ]
        ];
    }

    public function messages()
    {
        return [
            'sku_id.required'=>'请选择商品规格'
        ];
    }
}
