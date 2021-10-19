<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\User;
use App\Models\Work;
use App\Services\UserService;


class TaskController extends Controller
{
    public function read(Request $request)
    {
        $loginInfo = (new UserService())->getLoginInfoByToken($request['token']);
        
        $tasks = Task::where('task_room_id', $loginInfo['user_room_id'])
        ->where('task_is_everyday',1)
        ->select('task_id', 'task_default_minute', 'task_name','task_is_everyday')
        ->get(); 

        foreach($tasks as $task){

            $works = Work::where('work_room_id', $loginInfo['user_room_id'])
            ->where('work_task_id', $task['task_id'])
            ->whereYear('work_date', $request['year'])
            ->whereMonth('work_date', $request['month'])
            ->whereDay('work_date', $request['day'])
            ->select('work_id', 'work_date', 'work_minute','work_user_id')
            ->get();

            foreach($works as $work){
                $work['work_user_name'] = User::find($work['work_user_id'])->name;
                $work['work_user_img'] = User::find($work['work_user_id'])->user_img;
            }

            $task['works'] = $works;

        }
        return $tasks;
    }
    public function create(Request $request, Task $task)
    {
        $loginInfo = (new UserService())->getLoginInfoByToken($request['token']);

        if(isset($request["taskId"])){
            $task->where("task_id", $request["taskId"])->update([
                "task_name" => $request["taskName"],
                "task_default_minute" => $request["taskDefaultMinute"],
                "task_is_everyday" => $request["taskIsEveryday"],
                "task_room_id" => $loginInfo['user_room_id'],
            ]);
        }else{
            $task["task_name"] = $request["taskName"];
            $task["task_default_minute"] = $request["taskDefaultMinute"];
            $task["task_is_everyday"] = $request["taskIsEveryday"];
            $task["task_room_id"] = $loginInfo['user_room_id'];
            $task->save();
        }

        return $request;
    }
    public function delete(Request $request, Task $task)
    {
        $loginInfo = (new UserService())->getLoginInfoByToken($request['token']);

        Task::where('task_id', $request['task_id'])
        ->where('task_room_id', $loginInfo['user_room_id'])
        ->delete();

        Work::where('work_task_id', $request['task_id'])
        ->where('work_room_id', $loginInfo['user_room_id'])
        ->delete();

        return $request;
    }
}
