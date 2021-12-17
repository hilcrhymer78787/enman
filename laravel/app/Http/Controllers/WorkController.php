<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserService;
use App\Services\TaskService;
use App\Models\Work;
use App\Models\Task;
use App\Models\Invitation;

class WorkController extends Controller
{
    public function read(Request $request)
    {
        $loginInfo = (new UserService())->getLoginInfoByToken($request->header('token'));
        function getQuery($request)
        {
            $loginInfo = (new UserService())->getLoginInfoByToken($request->header('token'));
            return Work::where('work_room_id', $loginInfo['user_room_id'])
                ->whereYear('work_date', $request['year'])
                ->whereMonth('work_date', $request['month']);
            // ->whereBetween("work_date", ['2021-12-01', '2021-12-31']);
        }
        // 合計値
        $return['minute'] = (int) getQuery($request)->sum('work_minute');

        // 日別データ
        $return['days'] = getQuery($request)->selectRaw('sum(work_minute) as `minute`, work_date')
            ->groupByRaw('work_date')
            ->get();
        foreach ($return['days'] as $work) {
            $work['users'] = (new UserService())->getJoinedUsersByRoomId($loginInfo['user_room_id']);
            foreach ($work['users'] as $user) {
                $user['minute'] = (int)Work::where('work_room_id', $loginInfo['user_room_id'])
                    ->where('work_date', $work['work_date'])
                    ->where('work_user_id', $user['id'])
                    ->sum('work_minute');
            }
        }

        // ユーザー別データ
        $return['users'] = (new UserService())->getJoinedUsersByRoomId($loginInfo['user_room_id']);
        foreach ($return['users'] as $user) {
            $user['minute'] = (int) getQuery($request)
                ->where('work_user_id', $user['id'])
                ->sum('work_minute');
            $user['ratio'] = $return['minute'] ? $user['minute'] / $return['minute'] : 0;
            $tasks = (new TaskService())->getTasksByRoomId($loginInfo['user_room_id']);
            foreach ($tasks as $task) {
                $task['minute'] = (int)getQuery($request)->where('work_task_id', $task['task_id'])
                    ->where('work_user_id', $user['id'])
                    ->sum('work_minute');
                $task['ratio'] = $user['minute'] ? $task['minute'] / $user['minute'] : 0;
            }
            $user['datas'] = $tasks;
        }

        // タスク別のデータ
        $return['tasks'] = (new TaskService())->getTasksByRoomId($loginInfo['user_room_id']);
        foreach ($return['tasks'] as $task) {
            $task['minute'] = (int)getQuery($request)->where('work_task_id', $task['task_id'])
                ->sum('work_minute');
            $task['ratio'] = $return['minute'] ? $task['minute'] / $return['minute'] : 0;
            $users = (new UserService())->getJoinedUsersByRoomId($loginInfo['user_room_id']);
            foreach ($users as $user) {
                $user['minute'] = (int)getQuery($request)->where('work_task_id', $task['task_id'])
                    ->where('work_user_id', $user['id'])
                    ->sum('work_minute');
                $user['ratio'] = $task['minute'] ? $user['minute'] / $task['minute'] : 0;
            }
            $task['datas'] = $users;
        }

        return $return;
    }
    public function create(Request $request)
    {
        $loginInfo = (new UserService())->getLoginInfoByToken($request->header('token'));
        Work::where('work_date', $request['date'])
            ->where('work_task_id', $request['task_id'])
            ->delete();

        foreach ($request['works'] as $work) {
            Work::create([
                'work_date' => $request['date'],
                'work_room_id' => $loginInfo['user_room_id'],
                'work_task_id' => $request['task_id'],
                'work_user_id' => $work['work_user_id'],
                'work_minute' => $work['work_minute'],
            ]);
        }
        return $request;
    }
    public function delete(Request $request)
    {
        $loginInfo = (new UserService())->getLoginInfoByToken($request->header('token'));
        Work::where('work_date', $request['date'])
            ->where('work_task_id', $request['task_id'])
            ->where('work_room_id', $loginInfo['user_room_id'])
            ->delete();
    }
}
