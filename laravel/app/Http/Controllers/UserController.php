<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Str;


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
    public function create(Request $request, User $user)
    {
        $userDataCount = count(User::where('email', $request["email"])->get());

        if($userDataCount == 0){
            $user["name"] = $request["name"];
            $user["email"] = $request["email"];
            $user["password"] = $request["password"];
            $user["user_img"] = "https://picsum.photos/500/300?image=40";
            $user["token"] = $request["email"].Str::random(100);
            $user->save();
            return;
        }else{
            $error['errorMessage'] = 'このメールアドレスは既に登録されています';
            return $error;
        }
    }
    public function read(Request $request)
    {
        $userRoomId = User::where('token', $request->token)
        ->get()[0]->user_room_id;

        $users = User::where('user_room_id', $userRoomId)->get();
        return $users;
    }
    public function update(Request $request, User $user)
    {
        $userId = User::where('token', $request->token)
        ->get()[0]->id;

        $task->where("id", $userId)->update([
            "name" => $request["name"],
            "email" => $request["email"],
            // "password" => $request["password"],
            "user_img" => $request["user_img"],
        ]);
    }
}
