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
            $data = User::where('token', $request->token)
            ->leftjoin('rooms', 'users.user_room_id', '=', 'rooms.room_id')
            ->get()[0];
        }elseif($request->email){
            $data = User::where('email', $request->email)
            ->where('password',$request->password)
            ->leftjoin('rooms', 'users.user_room_id', '=', 'rooms.room_id')
            ->get()[0];
        }
        return $data;
    }
    public function create(Request $request, User $user)
    {
        if($request["id"] == 0){
            // 新規登録
            $userDataCount = count(User::where('email', $request["email"])->get());
            if($userDataCount != 0){
                $error['errorMessage'] = 'このメールアドレスは既に登録されています';
                return $error;
            }
            else{
                $user["name"] = $request["name"];
                $user["email"] = $request["email"];
                $user["password"] = $request["password"];
                $user["user_img"] = $request["user_img"];
                $user["token"] = $request["email"].Str::random(100);
                $user->save();
                return;
            }
        }else{
            // 編集
            $userData = User::where('token', $request["token"])
            ->get()[0];

            $userId = $userData['id'];
            $userEmail = $userData['email'];
            $userDataCount = count(User::where('email', $request["email"])->get());

            if($userDataCount != 0 && $userData['email'] != $request["email"]){
                $error['errorMessage'] = 'このメールアドレスは既に登録されています';
                return $error;
            }else{
                $user->where("id", $userId)->update([
                    "name" => $request["name"],
                    "email" => $request["email"],
                    "password" => $request["password"],
                    "user_img" => $request["user_img"],
                    "token" => $request["email"].Str::random(100),
                ]);
            }
        }
    }
    public function read(Request $request)
    {
        $userRoomId = User::where('token', $request->token)
        ->get()[0]->user_room_id;

        $users = User::where('user_room_id', $userRoomId)->get();
        return $users;
    }
    public function delete(Request $request)
    {
        $userId = User::where('token', $request->token)
        ->get()[0]->id;

        User::where('id', $userId)->delete();
    }
}
