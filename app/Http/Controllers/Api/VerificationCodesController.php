<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Overtrue\EasySms\EasySms;
use App\Http\Requests\Api\VerificationCodeRequest;
use App\Models\User;

class VerificationCodesController extends Controller
{
    public function store(VerificationCodeRequest $request, EasySms $easySms)
    {
    	$phone=$request->phone;
    	if(!app()->environment('production')){
    		$code = '1234';
    	}else{
    		//随机数
	    	$code = str_pad(random_int(1, 9999), 4, 0, STR_PAD_LEFT);
	    	
	    	try{
	    		$result = $easySms->send($phone, [
	                'content'  =>  "【恒成东兴】您的验证码是{$code}。如非本人操作，请忽略本短信"
	            ]);
	    	}catch (\Overtrue\EasySms\Exceptions\NoGatewayAvailableException $exception) {
	            $message = $exception->getException('yunpian')->getMessage();
	            return $this->response->errorInternal($message ?? '短信发送异常');
	        }
    	}

    	

        $key = 'verificationCode_'.str_random(15);
        $expiredAt = now()->addMinutes(10);

         \Cache::put($key, ['phone' => $phone, 'code' => $code], $expiredAt);

         return $this->response->array([
            'key' => $key,
            'expired_at' => $expiredAt->toDateTimeString(),
        ])->setStatusCode(201);
    }

    public function forget_store(Request $request, EasySms $easySms)
    {
        $phone=$request->phone;
        
        if(!$phone){
            return $this->response->error('请填写手机号', 422);
        }
        if(!$user=User::where('phone',$phone)->first()){
            return $this->response->error('此用户不存在', 422);
        }

        if(!app()->environment('production')){
            $code = '1234';
        }else{
            //随机数
            $code = str_pad(random_int(1, 9999), 4, 0, STR_PAD_LEFT);
            
            try{
                $result = $easySms->send($phone, [
                    'content'  =>  "【恒成东兴】您的验证码是{$code}。如非本人操作，请忽略本短信"
                ]);
            }catch (\Overtrue\EasySms\Exceptions\NoGatewayAvailableException $exception) {
                $message = $exception->getException('yunpian')->getMessage();
                return $this->response->errorInternal($message ?? '短信发送异常');
            }
        }

        

        $key = 'verificationCode_'.str_random(15);
        $expiredAt = now()->addMinutes(10);

         \Cache::put($key, ['phone' => $phone, 'code' => $code], $expiredAt);

         return $this->response->array([
            'key' => $key,
            'expired_at' => $expiredAt->toDateTimeString(),
        ])->setStatusCode(201);
    }
}
