<?php

namespace App\Services;
use App\Models\User;

class UserService
{
    public function getLoginInfoByToken($token)
    {
        $loginInfo = User::where('token', $token)->first();
        return $loginInfo;
    }
}