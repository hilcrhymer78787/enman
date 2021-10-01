<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\Invitation;
use App\Models\User;
use Illuminate\Support\Str;

class RoomController extends Controller
{
    public function read(Request $request)
    {
        $userId = User::where('token', $request->token)
        ->get()[0]->id;

        $rooms = Invitation::where('invitation_to_user_id', $userId)
        ->where('invitation_status', 2)
        ->leftjoin('rooms', 'invitations.invitation_room_id', '=', 'rooms.room_id')
        ->select('invitation_id', 'room_id', 'room_name', 'room_img')
        ->get();

        foreach($rooms as $room){
            $room["users"] = Invitation::where('invitation_room_id', $room["room_id"])
            ->where('invitation_status', 2)
            ->leftjoin('users', 'invitations.invitation_to_user_id', '=', 'users.id')
            ->select('name as user_name', 'id as user_id', 'user_img','invitation_id')
            ->get();
        }

        return $rooms;
    }

    public function create(Request $request, Room $room, User $user, Invitation $invitation)
    {
        $userId = User::where('token', $request->token)
        ->get()[0]->id;

        $roomToken = date('Y-m-d H:i:s').Str::random(100);

        if($request["room_id"] == 0){
            // 新規作成
            $room["room_name"] = $request["room_name"];
            $room["room_img"] = $request["room_img"];
            $room["room_token"] = $roomToken;
            $room->save();

            $roomId = Room::where('room_token', $roomToken)->get()[0]->room_id;

            $invitation['invitation_room_id'] = $roomId;
            $invitation['invitation_from_user_id'] = $userId;
            $invitation['invitation_to_user_id'] = $userId;
            $invitation['invitation_status'] = 2;
            $invitation->save();

            $user->where("id", $userId)->update([
                "user_room_id" => $roomId,
            ]);
        }else{
            // 編集
            $room->where("room_id", $request["room_id"])->update([
                "room_name" => $request["room_name"],
                "room_img" => $request["room_img"],
            ]);
        }

        // $rooms = Invitation::where('invitation_to_user_id', $userId)
        // ->where('invitation_status', 2)
        // ->leftjoin('rooms', 'invitations.invitation_room_id', '=', 'rooms.room_id')
        // ->select('invitation_id', 'room_id', 'room_name', 'room_img')
        // ->get();

        // foreach($rooms as $room){
        //     $room["users"] = Invitation::where('invitation_room_id', $room["room_id"])
        //     ->where('invitation_status', 2)
        //     ->leftjoin('users', 'invitations.invitation_to_user_id', '=', 'users.id')
        //     ->select('name as user_name', 'id as user_id', 'user_img','invitation_id')
        //     ->get();
        // }
    }
}
