<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Services\UserService;
use App\Services\InvitationService;

class RoomController extends Controller
{

    public function create(Request $request)
    {

        if ($request['room_id'] == 0) {
            // 新規作成
            $roomToken = date('Y-m-d H:i:s') . Str::random(100);
            Room::create([
                'room_name' => $request['room_name'],
                'room_img' => $request['room_img'],
                'room_token' => $roomToken,
            ]);
            if ($request['exist_file']) {
                $request['file']->storeAs('public/', $request['room_img']);
            }
            // 自分自身をルームに招待し入室
            $roomId = Room::where('room_token', $roomToken)->first()->room_id;
            $loginInfo = (new UserService())->getLoginInfoByToken($request->header('token'));
            (new InvitationService())->invitateMySelf($roomId, $loginInfo['id']);
            return;
        }
        // 編集
        Room::where('room_id', $request['room_id'])->update([
            'room_name' => $request['room_name'],
            'room_img' => $request['room_img'],
        ]);
        if ($request['exist_file']) {
            $request['file']->storeAs('public/', $request['room_img']);
        }
        if ($request['room_img'] != $request['img_oldname']) {
            Storage::delete('public/' . $request['img_oldname']);
        }
        return;
    }
}
