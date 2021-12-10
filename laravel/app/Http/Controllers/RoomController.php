<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\Invitation;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Services\UserService;
use App\Services\InvitationService;

class RoomController extends Controller
{

    public function create(Request $request, Room $room, User $user, Invitation $invitation)
    {
        $loginInfo = (new UserService())->getLoginInfoByToken($request->header('token'));
        $roomToken = date('Y-m-d H:i:s') . Str::random(100);

        if ($request['room_id'] == 0) {
            // 新規作成
            $room['room_name'] = $request['room_name'];
            $room['room_img'] = $request['room_img'];
            $room['room_token'] = $roomToken;
            $room->save();
            if ($request['exist_file']) {
                $request['file']->storeAs('public/', $request['room_img']);
            }

            $roomId = Room::where('room_token', $roomToken)->first()->room_id;

            // 自分自身をルームに招待し参加
            (new InvitationService())->invitateMySelf($roomId, $loginInfo['id']);

            // ルームに入室
            $user->where('id', $loginInfo['id'])->update([
                'user_room_id' => $roomId,
            ]);
        } else {
            // 編集
            $room->where('room_id', $request['room_id'])->update([
                'room_name' => $request['room_name'],
                'room_img' => $request['room_img'],
            ]);
            if ($request['exist_file']) {
                $request['file']->storeAs('public/', $request['room_img']);
            }
            if ($request['room_img'] != $request['img_oldname']) {
                Storage::delete('public/' . $request['img_oldname']);
            }
        }
    }
}
