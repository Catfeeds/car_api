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
        switch($this->method()){
            case 'POST':
                return [
                    'name'=>'required|between:3,25|regex:/^[A-Za-z0-9\-\_]+$/|unique:users,name',
                    'password'=>'required|string|min:6',
                    'verification_key' => 'required|string',
                    'verification_code' => 'required|string',
                ];
            break;

            case 'PUT':
                return [
                    'name'=>'required|between:3,25|regex:/^[A-Za-z0-9\-\_]+$/|unique:users,name',
                    'username'=>'required',
                    'id_number'=>"required",
                    'address'=>'required'
                ];   
            break;
        }
        
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
            'password.min'=>'请最少输入6位密码',
            'address.required'=>'地址不能为空',
            'username.required'=>'姓名不能为空',
            'id_number.required'=>'身份证号不能为空',
            'id_number.regex'=>'身份证号格式不正确'
        ];
    }
}
