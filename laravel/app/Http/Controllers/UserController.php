<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Room;
use App\Models\Invitation;
use Illuminate\Support\Str;


class UserController extends Controller
{
    public function read(Request $request)
    {
        if($request->token){
            // トークン認証
            $loginInfo = User::where('token', $request->token)
            ->leftjoin('rooms', 'users.user_room_id', '=', 'rooms.room_id')
            ->select('id', 'name', 'email', 'user_img', 'room_id','room_img','room_name','token')
            ->first();
        }elseif($request->email){
            // ベーシック認証
            $loginInfo = User::where('email', $request->email)
            ->where('password',$request->password)
            ->leftjoin('rooms', 'users.user_room_id', '=', 'rooms.room_id')
            ->select('id', 'name', 'email', 'user_img', 'room_id','room_img','room_name','token')
            ->first();
        }

        // 参加しているユーザー
        $loginInfo["room_joined_users"] = Invitation::where('invitation_room_id', $loginInfo['room_id'])
        ->where('invitation_status', 2)
        ->leftjoin('users', 'invitations.invitation_to_user_id', '=', 'users.id')
        ->select('id', 'name', 'email', 'user_img')
        ->get();

        // 招待中のユーザー
        $loginInfo["room_inviting_users"] = Invitation::where('invitation_room_id', $loginInfo['room_id'])
        ->where('invitation_status', '<', 2)
        ->leftjoin('users', 'invitations.invitation_to_user_id', '=', 'users.id')
        ->select('id', 'name', 'email', 'user_img')
        ->get();

        // 参加しているルーム
        $loginInfo["rooms"] = Invitation::where('invitation_to_user_id', $loginInfo['id'])
        ->where('invitation_status', 2)
        ->leftjoin('rooms', 'invitations.invitation_room_id', '=', 'rooms.room_id')
        ->select('invitation_id', 'room_id', 'room_name', 'room_img')
        ->get();

        return $loginInfo;
    }
    public function create(Request $request, Room $room, User $user, Invitation $invitation)
    {
        if($request["id"] == 0){
            // 新規登録
            $userDataCount = count(User::where('email', $request["email"])->get());
            if($userDataCount != 0){
                $error['errorMessage'] = 'このメールアドレスは既に登録されています';
                return $error;
            }
            else{

                // 部屋を作成

                $roomToken = date('Y-m-d H:i:s').Str::random(100);

                $room["room_name"] = "マイルーム";
                $room["room_img"] = "https://picsum.photos/500/300?image=40";
                $room["room_token"] = $roomToken;
                $room->save();
    
                $roomId = Room::where('room_token', $roomToken)->get()[0]->room_id;

                // ユーザー作成

                $userToken = $request["email"].Str::random(100);

                $user["name"] = $request["name"];
                $user["email"] = $request["email"];
                $user["password"] = $request["password"];
                $user["user_img"] = $request["user_img"];
                $user["token"] = $userToken;
                $user["user_room_id"] = $roomId;
                $user->save();

                $userId = User::where('token', $userToken)->get()[0]->id;

                // 招待

                $invitation['invitation_room_id'] = $roomId;
                $invitation['invitation_from_user_id'] = $userId;
                $invitation['invitation_to_user_id'] = $userId;
                $invitation['invitation_status'] = 2;
                $invitation->save();

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
    public function updateRoomId(Request $request)
    {
        $userId = User::where('token', $request->token)
        ->get()[0]->id;

        User::where("id", $userId)->update([
            "user_room_id" => $request["room_id"],
        ]);
    }
    public function delete(Request $request)
    {
        $userId = User::where('token', $request->token)
        ->get()[0]->id;

        User::where('id', $userId)->delete();
    }
}
