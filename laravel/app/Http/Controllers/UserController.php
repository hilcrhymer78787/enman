<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function test()
    {
        $data = [
            "test1",
            "test2",
            "test3",
            "test4",
            "test5",
        ];
        return $data;
    }
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
