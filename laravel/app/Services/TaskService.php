<?php

namespace App\Services;

use App\Models\Task;

class TaskService
{
    public function getTasksByRoomId($roomId)
    {
        $tasks = Task::where('task_room_id', $roomId)
            ->where('task_is_everyday', 1)
            ->select('task_id', 'task_default_minute', 'task_name', 'task_is_everyday', 'task_sort_key')
            ->orderBy('task_sort_key')
            ->get();
        return $tasks;
    }
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
