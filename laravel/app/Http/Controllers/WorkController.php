<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Work;
use App\Models\User;

class WorkController extends Controller
{
    public function read()
    {
        $data = Work::get();
        return $data;
    }
    public function create(Request $request)
    {
        $userRoomId = User::where('token', $request->token)
        ->get()[0]->user_room_id;

        Work::where('work_date', $request["date"])
        ->where('work_task_id', $request["task_id"])->
        delete();

        $works = $request['works'];
        foreach($works as $work){
            $newWork = new Work;
            $newWork["work_date"] = $request["date"];
            $newWork["work_room_id"] = $userRoomId;
            $newWork["work_task_id"] = $request["task_id"];
            $newWork["work_user_id"] = $work["work_user_id"];
            $newWork["work_minute"] = $work["work_minute"];
            $newWork->save();
        }
        return $request;
    }
    public function delete(Request $request)
    {
        $userRoomId = User::where('token', $request->token)
        ->get()[0]->user_room_id;

        Work::where('work_date', $request["date"])
        ->where('work_task_id', $request["task_id"])
        ->where('work_room_id', $userRoomId)
        ->delete();
    }
}
