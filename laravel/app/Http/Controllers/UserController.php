<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function login_info(Request $request)
    {
        if($request->token){
            $data = User::where('token', $request->token)->get();
        }elseif($request->email){
            $data = User::where('email', $request->email)
            ->where('password',$request->password)->get();
        }
        return $data[0];
    }
    public function read(Request $request)
    {
        $userRoomId = User::where('token', $request->token)
        ->get()[0]->user_room_id;

        $users = User::where('user_room_id', $userRoomId)->get();
        return $users;
    }
}
