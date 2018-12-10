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
                    'name'=>'required|between:3,18|regex:/^[A-Za-z0-9\-\_]+$/|unique:users,name',
                    'password'=>'required|string|min:6',
                    'verification_key' => 'required|string',
                    'verification_code' => 'required|string',
                ];
            break;

            case 'PUT':
                return [
                    'username'=>'required',
                    'id_number'=>[
                            'required',
                            'regex:/(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/',
                        ],
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
            'verification_key.required'=>'短信验证码已失效',
            'verification_code.required'=>'请填写短信验证码',
            'name.required'=>'请填写用户名',
            'password.required'=>'请填写密码',
            'name.unique'=>'用户名已存在',
            'name.between'=>'请输入3-18位用户名',
            'name.regex'=>'用户名格式错误',
            'password.min'=>'请最少输入6位密码',
            'address.required'=>'地址不能为空',
            'username.required'=>'姓名不能为空',
            'id_number.required'=>'身份证号不能为空',
            'id_number.regex'=>'身份证号格式不正确',
        ];
    }
}
