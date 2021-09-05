<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Work;

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

        $works = $request['works'];
        foreach($works as $work){

    
            $work = new Work;
            $work["work_task_id"] = $request["task_id"];
            $work["work_minute"] = $request["work_minute"];
            $work["work_user_id"] = $request["work_user_id"];
            $work["work_room_id"] = $userRoomId;
            // $work->save();
        }
        return $works;
    }
}
