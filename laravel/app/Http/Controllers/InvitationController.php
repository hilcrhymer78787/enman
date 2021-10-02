<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invitation;
use App\Models\Room;
use App\Models\User;

class InvitationController extends Controller
{
    public function create(Request $request, Room $room, User $user, Invitation $invitation)
    {
        $toUserData = User::where('email', $request["email"])->get();
        if(count($toUserData) == 0){
            $error['errorMessage'] = '登録されているメールアドレスはありません';
            return $error;
        }else{
            $fromUserData = User::where('token', $request["token"])->get()[0];
            $toUserData = $toUserData[0];
            $roomData = Room::where('room_id', $fromUserData['user_room_id'])->get()[0];

            // 重複判定
            $bool = Invitation::where('invitation_room_id', $fromUserData['user_room_id'])
            ->where('invitation_to_user_id', $toUserData['id'])->first();
            if(isset($bool)){
                $error['errorMessage'] = $toUserData['name'].'さんはすでに'.$roomData['room_name'].'へ招待されています';
                return $error;
            }

            $invitation["invitation_room_id"] = $fromUserData['user_room_id'];
            $invitation["invitation_from_user_id"] = $fromUserData['id'];
            $invitation["invitation_to_user_id"] = $toUserData['id'];
            $invitation["invitation_status"] = 0;
            $invitation->save();

            $success['successMessage'] = $toUserData['name'].'さんを'.$roomData['room_name'].'へ招待しました';
            return $success;
        }
    }
    public function read()
    {
        $data = Invitation::get();
        return $data;
    }
    public function update(Request $request, Invitation $invitation)
    {
        $loginInfo = User::where('token', $request["token"])->first();
        if(!isset($loginInfo)){
            return;
        }

        $invitation->where("invitation_id", $request["invitation_id"])->update([
            "invitation_status" => 2,
        ]);

        $invitationData = Invitation::where('invitation_id', $request["invitation_id"])->first();

        User::where("id", $loginInfo['id'])->update([
            "user_room_id" => $invitationData['invitation_room_id'],
        ]);
    }
    public function delete(Request $request, Invitation $invitation)
    {
        $loginInfo = User::where('token', $request["token"])->first();
        if(!isset($loginInfo)){
            return;
        }

        Invitation::where('invitation_id', $request['invitation_id'])
        ->delete();
    }
}
