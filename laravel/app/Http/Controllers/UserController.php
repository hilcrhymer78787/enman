<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Room;
use App\Models\Invitation;
use App\Services\UserService;
use Illuminate\Support\Str;


class UserController extends Controller
{
    public function read(Request $request)
    {
        // 個人情報取得
        if($request->token){
            // トークン認証
            $loginInfo = User::where('token', $request->token)
            ->leftjoin('rooms', 'users.user_room_id', '=', 'rooms.room_id')
            ->select('id', 'name', 'email', 'user_img', 'room_id','room_img','room_name','token')
            ->first();
            if(!isset($loginInfo)){
                $error['errorMessage'] = 'このトークンは有効ではありません';
                return $error;
            }
        }elseif($request->email){
            // ベーシック認証
            $loginInfo = User::where('email', $request->email)
            ->where('password',$request->password)
            ->leftjoin('rooms', 'users.user_room_id', '=', 'rooms.room_id')
            ->select('id', 'name', 'email', 'user_img', 'room_id','room_img','room_name','token')
            ->first();
            if(!isset($loginInfo)){
                $error['errorMessage'] = 'メールアドレスかパスワードが違います';
                return $error;
            }
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

        // 招待されている部屋(未確認)
        $loginInfo["invited_rooms"] = Invitation::where('invitation_to_user_id', $loginInfo['id'])
        ->where('invitation_status', '<', 2)
        ->leftjoin('rooms', 'invitations.invitation_room_id', '=', 'rooms.room_id')
        ->leftjoin('users', 'invitations.invitation_from_user_id', '=', 'users.id')
        ->select('invitation_id', 'invitation_status', 'room_id', 'room_name', 'room_img', 'name')
        ->get();

            foreach($loginInfo["invited_rooms"] as $room){
                // 参加しているユーザー
                $room["joined_users"] = Invitation::where('invitation_room_id', $room["room_id"])
                ->where('invitation_status', 2)
                ->leftjoin('users', 'invitations.invitation_to_user_id', '=', 'users.id')
                ->select('id', 'name')
                ->get();
                // 招待中のユーザー
                $room["inviting_users"] = Invitation::where('invitation_room_id', $room["room_id"])
                ->where('invitation_status', '<', 2)
                ->leftjoin('users', 'invitations.invitation_to_user_id', '=', 'users.id')
                ->select('id', 'name')
                ->get();
            }
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

                $loginInfo = (new UserService())->getLoginInfoByToken($userToken);

                // 招待
                $invitation['invitation_room_id'] = $roomId;
                $invitation['invitation_from_user_id'] = $loginInfo['id'];
                $invitation['invitation_to_user_id'] = $loginInfo['id'];
                $invitation['invitation_status'] = 2;
                $invitation->save();

                return;
            }
        }else{
            // 編集
            $loginInfo = (new UserService())->getLoginInfoByToken($request->token);
            if(!isset($loginInfo)){
                $error['errorMessage'] = 'このトークンは有効ではありません';
                return $error;
            }

            $loginInfoCount = count(User::where('email', $request["email"])->get());

            if($loginInfoCount != 0 && $loginInfo['email'] != $request["email"]){
                $error['errorMessage'] = 'このメールアドレスは既に登録されています';
                return $error;
            }else{
                $user->where("id", $loginInfo['id'])->update([
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
        $loginInfo = (new UserService())->getLoginInfoByToken($request->token);
        if(!isset($loginInfo)){
            return $error['errorMessage'] = 'このトークンは有効ではありません';
        }
        User::where("id", $loginInfo['id'])->update([
            "user_room_id" => $request["room_id"],
        ]);
    }
    public function delete(Request $request)
    {
        $loginInfo = (new UserService())->getLoginInfoByToken($request->token);
        if(!isset($loginInfo)){
            return $error['errorMessage'] = 'このトークンは有効ではありません';
        }

        User::where('id', $loginInfo['id'])->delete();
    }
}
