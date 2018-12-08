<?php

namespace App\Http\Requests\Api;

class AuthorizationRequest extends Request
{
    

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'username'=>'required|string',
            'password'=>'required|string|min:6'
        ];
    }

    public function messages()
    {
        return [
            'username.required'=>'用户名不能为空',
            'password.required'=>'密码不能为空',
            'password.min'=>'密码最少为6位'
        ];
    }
}
