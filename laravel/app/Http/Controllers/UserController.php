<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Room;
use App\Models\Invitation;
use App\Services\UserService;
use App\Services\TaskService;
use App\Services\InvitationService;
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
    public function create(Request $request)
    {
        if (!$request['id']) {
            // 重複確認
            $existEmail = User::where('email', $request['email'])->first();
            if ($existEmail) {
                $error['errorMessage'] = 'このメールアドレスは既に登録されています';
                return $error;
            }
            // 部屋を作成
            $room = Room::create([
                'room_name' => 'マイルーム',
                'room_img' => 'https://picsum.photos/500/300?image=40',
                'room_token' => date('Y-m-d H:i:s') . Str::random(100),
            ]);
            // ダミータスクを作成
            (new TaskService())->createcDummyTask($room['id']);
            // 新規ユーザー登録
            $user = User::create([
                'name' => $request['name'],
                'email' => $request['email'],
                'password' => $request['password'],
                'user_img' => $request['user_img'],
                'token' => $request['email'] . Str::random(100),
            ]);
            if ($request['exist_file']) {
                $request['file']->storeAs('public/', $request['user_img']);
            }
            // 自分自身をルームに招待し入室
            (new InvitationService())->invitateMySelf($room['id'], $user['id']);
            return;
        }

        // 編集の場合
        $loginInfo = (new UserService())->getLoginInfoByToken($request->header('token'));
        // 重複確認
        $existEmail = User::where('email', $request['email'])->first();
        if ($existEmail && $loginInfo['email'] != $request['email']) {
            $error['errorMessage'] = 'このメールアドレスは既に登録されています';
            return $error;
        }
        // ユーザー情報編集
        User::where('id', $loginInfo['id'])->update([
            'name' => $request['name'],
            'email' => $request['email'],
            'user_img' => $request['user_img'],
        ]);
        if ($request['password']) {
            User::where('id', $request['id'])->update([
                'password' => $request['password'],
            ]);
        }
        if ($request['exist_file']) {
            $request['file']->storeAs('public/', $request['user_img']);
        }
        if ($request['user_img'] != $request['img_oldname']) {
            Storage::delete('public/' . $request['img_oldname']);
        }
        return;
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
