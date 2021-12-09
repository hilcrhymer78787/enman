<?php

namespace App\Services;
use App\Models\Task;

class TaskService
{
    public function createcDummyTask($roomId)
    {
        $task = new Task;
        $task["task_name"] = '洗い物';
        $task["task_default_minute"] = 15;
        $task["task_is_everyday"] = 1;
        $task["task_room_id"] = $roomId;
        $task->save();
        
        $task = new Task;
        $task["task_name"] = '料理';
        $task["task_default_minute"] = 30;
        $task["task_is_everyday"] = 1;
        $task["task_room_id"] = $roomId;
        $task->save();

        $task = new Task;
        $task["task_name"] = '洗濯';
        $task["task_default_minute"] = 15;
        $task["task_is_everyday"] = 1;
        $task["task_room_id"] = $roomId;
        $task->save();
    }
}
