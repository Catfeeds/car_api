<?php

namespace App\Http\Requests\Api;

class UserRequest extends Request
{
   

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'=>'required|between:3,25|regex:/^[A-Za-z0-9\-\_]+$/|unique:users,name',
            'password'=>'required|string|min:6',
            'verification_key' => 'required|string',
            'verification_code' => 'required|string',
        ];
    }

    public function attributes()
    {
        return [
            'verification_key' => '短信验证码 key',
            'verification_code' => '短信验证码',
        ];
    }

    public function messages()
    {
        return [
            'name.unique'=>'用户名已存在',
            'verification_code.require'=>'请填写手机验证码',
            'password.required'=>'请填写密码',
            'password.min'=>'请最少输入6位密码'
        ];
    }
}
