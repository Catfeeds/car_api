<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests\Api\AuthorizationRequest;
use App\Transformers\UserTransformer;
use App\Models\User;

class AuthorizationsController extends Controller
{
    public function store(AuthorizationRequest $request)
    {
    	$credentials['phone']=$request->username;
    	$credentials['password']=$request->password;
    	
    	if(!$token=\Auth::guard('api')->attempt($credentials)){
    		return $this->response->errorUnauthorized('用户名或密码错误');
    	}
    	$user=User::where('phone',$credentials['phone'])->first();

    	return $this->response->item($user, new UserTransformer())
        ->setMeta([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => \Auth::guard('api')->factory()->getTTL() * 60
        ])
        ->setStatusCode(200);
    }

    public function update()
	{
	    $token = Auth::guard('api')->refresh();
	    return $this->respondWithToken($token);
	}

	public function destroy()
	{
	    Auth::guard('api')->logout();
	    return $this->response->noContent();
	}


	protected function respondWithToken($token)
	{
	    return $this->response->array([
	        'access_token' => $token,
	        'token_type' => 'Bearer',
	        'expires_in' => \Auth::guard('api')->factory()->getTTL() * 60
	    ]);
	}

}
