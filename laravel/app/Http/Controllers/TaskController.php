<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\User;
use App\Models\Work;


class TaskController extends Controller
{
    public function read(Request $request)
    {
        $data = Task::get();
        return $data;
    }
    public function show(Request $request)
    {
        //ログインユーザのルームID        
        $userRoomId = User::where('token', $request->token)
        ->get()[0]->user_room_id;
        
        //ログインユーザと同じルームに属するタスクのみ取得
        //とりあえず毎日タスク（task_is_everydayが１）だけ取得
        $datas = Task::where('task_room_id', $userRoomId)
        ->where('task_is_everyday',1)->get(); 
        
        dd($datas);
        
        
        foreach($datas as $data){
            //ログインユーザと同じルームに属するworkの中から
            $works = Work::where('work_room_id', $userRoomId)
            ->where('work_task_id', $data['task_id'])
            ->get();
            
            foreach($works as $work){
                $work['work_user_name'] = USER::find($work['work_user_id'])->name;
            }
            
            $data['works'] = $works;

            return $data;

        }
    }
    public function create(Request $request)
    {
        $userRoomId = User::where('token', $request->token)
        ->get()[0]->user_room_id;

        $task = new Task;
        $task["task_name"] = $request["taskName"];
        $task["task_default_minute"] = $request["taskDefaultMinute"];
        $task["task_point_per_minute"] = $request["taskPointPerMinute"];
        $task["task_is_everyday"] = $request["taskIsEveryday"];
        $task["task_room_id"] = $userRoomId;
        $task->save();

        return;
    }
}
