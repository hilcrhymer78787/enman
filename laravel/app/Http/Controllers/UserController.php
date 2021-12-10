<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Room;
use App\Services\UserService;
use App\Services\TaskService;
use App\Services\RoomService;
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
        $token = User::where('email', $request['email'])
            ->select('token')
            ->first();
        if (!$token) {
            return response()->json(['errorMessage' => 'このメールアドレスは登録されていません'], 500);
        }
        $token = User::where('email', $request['email'])
            ->where('password', $request['password'])
            ->select('token')
            ->first();
        if (!$token) {
            return response()->json(['errorMessage' => 'パスワードが間違っています'], 500);
        }
        return $token;
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
        $loginInfo['rooms'] = (new RoomService())->getJoinedRooms($loginInfo['id']);

        // 招待されている部屋
        $loginInfo['invited_rooms'] = (new RoomService())->getInvitedRooms($loginInfo['id']);

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
                return response()->json(['errorMessage' => 'このメールアドレスは既に登録されています',], 500);
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
            return response()->json(['errorMessage' => 'このメールアドレスは既に登録されています',], 404);
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
