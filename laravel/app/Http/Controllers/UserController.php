<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function read(Request $request)
    {
        if($request->token){
            $data = User::where('token', $request->token)->get();
        }elseif($request->email){
            $data = User::where('email', $request->email)
            ->where('password',$request->password)->get();
        }
        return $data[0];
    }
}
