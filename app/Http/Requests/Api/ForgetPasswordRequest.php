<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\Request;

class ForgetPasswordRequest extends Request
{
    

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'password'=>'required|string|min:6',
            'password.required'=>'密码不能为空',
            'password.min'=>'密码最少为6位'
        ];
    }
}
