<?php

namespace App\Transformers;

use App\Models\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
	public function transform(User $user)
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'phone'=>$user->phone,
            'username'=>$user->username,
            'id_number'=>$user->id_number,
            'address'=>$user->address
        ];
    }
}