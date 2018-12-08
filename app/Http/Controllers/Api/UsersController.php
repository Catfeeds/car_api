<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests\Api\UserRequest;
use App\Transformers\UserTransformer;
use App\Http\Requests\Api\ForgetPasswordRequest;
use App\Models\User;

class UsersController extends Controller
{
    public function store(UserRequest $request)
    {
    	 $verifyData = \Cache::get($request->verification_key);

    	 if (!$verifyData){
            return $this->response->error('验证码已失效', 422);
        }

        if (!hash_equals($verifyData['code'], $request->verification_code)) {
            // 返回401
            return $this->response->errorUnauthorized('验证码错误');
        }

        $user=User::create([
        	'name'=>$request->name,
        	'phone'=>$verifyData['phone'],
        	'password'=>bcrypt($request->password),
        ]);

        // 清除验证码缓存
        \Cache::forget($request->verification_key);

        return $this->response->item($user, new UserTransformer())
        ->setMeta([
            'access_token' => \Auth::guard('api')->fromUser($user),
            'token_type' => 'Bearer',
            'expires_in' => \Auth::guard('api')->factory()->getTTL() * 60
        ])
        ->setStatusCode(201);

    }

    public function show()
    {
        return $this->response->item($this->user(),new UserTransformer());
    }

    public function update(UserRequest $request)
    {
        $user=$this->user();
        $attributes = $request->only(['name', 'username', 'id_number','address']);
        $user->update($attributes);
        return $this->response->item($user, new UserTransformer())->setStatusCode(200);
    }

    public function forget(ForgetPasswordRequest $request)
    {
        $verifyData = \Cache::get($request->verification_key);
        if (!$verifyData){
            return $this->response->error('验证码已失效', 422);
        }
        
        if (!hash_equals($verifyData['code'], $request->verification_code)) {
            // 返回401
            return $this->response->errorUnauthorized('验证码错误');
        }
        $user=User::where('phone',$verifyData['phone'])->first();
        
        if(!$user)
        {
            return $this->response->errorUnauthorized('不存在该用户');
        }

        $user->update([
            'password'=>bcrypt($request->password),
        ]);
        // 清除验证码缓存
        \Cache::forget($request->verification_key);

        return [];
    }
}
