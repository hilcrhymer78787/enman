<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Room;
use App\Models\Invitation;
use App\Services\UserService;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;


class UserController extends Controller
{

    public function test_authentication()
    {
        $loginInfo = User::where('id', 1)
            ->select('token')
            ->first();
        return $loginInfo;
    }
    public function basic_authentication(Request $request)
    {
        // ベーシック認証
        $loginInfo = User::where('email', $request->email)
            ->where('password', $request->password)
            ->select('token')
            ->first();
        if (!isset($loginInfo)) {
            $error['errorMessage'] = 'メールアドレスかパスワードが違います';
            return $error;
        }
        return $loginInfo;
    }
    public function bearer_authentication(Request $request)
    {
        $loginInfo = (new UserService())->getLoginInfoByToken($request->header('token'));
        if (!$loginInfo) {
            $error['errorMessage'] = 'このトークンは有効ではありません';
            return $error;
        }

        // 参加しているユーザー
        $loginInfo['room_joined_users'] = (new UserService())->getJoinedUsersByRoomId($loginInfo['room_id']);

        // 招待中のユーザー
        $loginInfo['room_inviting_users'] = (new UserService())->getInvitingUsersByRoomId($loginInfo['room_id']);

        // 参加しているルーム
        $loginInfo['rooms'] = Invitation::where('invitation_to_user_id', $loginInfo['id'])
            ->where('invitation_status', 2)
            ->leftjoin('rooms', 'invitations.invitation_room_id', '=', 'rooms.room_id')
            ->select('invitation_id', 'room_id', 'room_name', 'room_img')
            ->get();

        // 招待されている部屋(未確認)
        $loginInfo['invited_rooms'] = Invitation::where('invitation_to_user_id', $loginInfo['id'])
            ->where('invitation_status', '<', 2)
            ->leftjoin('rooms', 'invitations.invitation_room_id', '=', 'rooms.room_id')
            ->leftjoin('users', 'invitations.invitation_from_user_id', '=', 'users.id')
            ->select('invitation_id', 'invitation_status', 'room_id', 'room_name', 'room_img', 'name')
            ->get();

        foreach ($loginInfo['invited_rooms'] as $room) {
            // 参加しているユーザー
            $room['joined_users'] = (new UserService())->getJoinedUsersByRoomId($room['room_id']);
            // 招待中のユーザー
            $room['inviting_users'] = (new UserService())->getInvitingUsersByRoomId($room['room_id']);
        }
        return $loginInfo;
    }
    public function create(Request $request, Room $room, User $user, Invitation $invitation)
    {
        if ($request['id'] == 0) {
            // 重複確認
            $userDataCount = count(User::where('email', $request['email'])->get());
            if ($userDataCount != 0) {
                $error['errorMessage'] = 'このメールアドレスは既に登録されています';
                return $error;
            }

            // 部屋を作成
            $roomToken = date('Y-m-d H:i:s') . Str::random(100);

            $room['room_name'] = 'マイルーム';
            $room['room_img'] = 'https://picsum.photos/500/300?image=40';
            $room['room_token'] = $roomToken;
            $room->save();

            $roomId = Room::where('room_token', $roomToken)->first()->room_id;

            // 新規登録
            $userToken = $request['email'] . Str::random(100);

            $user['name'] = $request['name'];
            $user['email'] = $request['email'];
            $user['password'] = $request['password'];
            $user['user_img'] = $request['user_img'];
            $user['token'] = $userToken;
            $user['user_room_id'] = $roomId;
            $user->save();
            if ($request['exist_file']) {
                $request['file']->storeAs('public/', $request['user_img']);
            }

            $loginInfo = (new UserService())->getLoginInfoByToken($userToken);

            // 自分自身をルームに招待し参加
            $invitation['invitation_room_id'] = $roomId;
            $invitation['invitation_from_user_id'] = $loginInfo['id'];
            $invitation['invitation_to_user_id'] = $loginInfo['id'];
            $invitation['invitation_status'] = 2;
            $invitation->save();

            return;
        } else {
            // 編集
            $loginInfo = (new UserService())->getLoginInfoByToken($request->header('token'));

            $loginInfoCount = count(User::where('email', $request['email'])->get());
            if ($loginInfoCount != 0 && $loginInfo['email'] != $request['email']) {
                $error['errorMessage'] = 'このメールアドレスは既に登録されています';
                return $error;
            }

            $user->where('id', $loginInfo['id'])->update([
                'name' => $request['name'],
                'email' => $request['email'],
                'user_img' => $request['user_img'],
            ]);
            if ($request['exist_file']) {
                $request['file']->storeAs('public/', $request['user_img']);
            }
            if ($request['user_img'] != $request['img_oldname']) {
                Storage::delete('public/' . $request['img_oldname']);
            }
            if ($request['password']) {
                $user->where('id', $request['id'])->update([
                    'password' => $request['password'],
                ]);
            }
            return User::where('id', $request['id'])->first();
        }
    }
    public function updateRoomId(Request $request)
    {
        $loginInfo = (new UserService())->getLoginInfoByToken($request->header('token'));
        User::where('id', $loginInfo['id'])->update([
            'user_room_id' => $request['room_id'],
        ]);
    }
    public function delete(Request $request)
    {
        $loginInfo = (new UserService())->getLoginInfoByToken($request->header('token'));
        User::where('id', $loginInfo['id'])->delete();
    }
}
