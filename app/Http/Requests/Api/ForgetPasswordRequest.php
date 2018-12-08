<?php

namespace App\Http\Requests\Api;

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
            'verification_key' => 'required|string',
            'verification_code' => 'required|string',
        ];
    }

    public function messages()
    {
        return [
            'verification_key.required'=>'短信验证码已失效',
            'verification_code.required'=>'请填写短信验证码',
            'password.required'=>'密码不能为空',
            'password.min'=>'密码最少为6位'
        ];
    }
}
