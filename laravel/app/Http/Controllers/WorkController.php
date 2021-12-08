<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserService;
use App\Models\Work;
use App\Models\Task;
use App\Models\Invitation;

class WorkController extends Controller
{
    public function read(Request $request)
    {
        $loginInfo = (new UserService())->getLoginInfoByToken($request->header('token'));

        // 1日ごとのデータ
        $works = Work::where('work_room_id', $loginInfo['user_room_id'])
            ->whereYear('work_date', $request['year'])
            ->whereMonth('work_date', $request['month'])
            ->selectRaw('sum(work_minute) as `sum_minute`, work_date')
            ->groupByRaw('work_date')
            ->get();
        foreach ($works as $work) {
            $work['users'] = (new UserService())->getJoinedUsersByRoomId($loginInfo['user_room_id']);
            foreach ($work['users'] as $user) {
                $minute = Work::where('work_room_id', $loginInfo['user_room_id'])
                    ->where('work_date', $work['work_date'])
                    ->where('work_user_id', $user['id'])
                    ->sum('work_minute');
                $user['minute'] = intval($minute);
            }
        }
        $data['daily'] = $works;

        // 月ごとのデータ
        $data['monthly'] = (new UserService())->getJoinedUsersByRoomId($loginInfo['user_room_id']);
        foreach ($data['monthly'] as $user) {
            $minute = Work::where('work_room_id', $loginInfo['user_room_id'])
                ->whereYear('work_date', $request['year'])
                ->whereMonth('work_date', $request['month'])
                ->where('work_user_id', $user['id'])
                ->sum('work_minute');
            $user['minute'] = intval($minute);
        }

        // 月ごとのタスクの合計
        $data['tasks'] = Task::where('task_room_id', $loginInfo['user_room_id'])
            ->where('task_is_everyday', 1)
            ->select('task_id', 'task_default_minute', 'task_name as name', 'task_is_everyday')
            ->orderBy('task_sort_key')
            ->get();
        foreach ($data['tasks'] as $task) {
            $minute = Work::where('work_room_id', $loginInfo['user_room_id'])
                ->whereYear('work_date', $request['year'])
                ->whereMonth('work_date', $request['month'])
                ->where('work_task_id', $task['task_id'])
                ->select('name', 'id', 'user_img')
                ->sum('work_minute');
            $task['minute'] = intval($minute);
        }

        return $data;
    }
    public function create(Request $request)
    {
        $loginInfo = (new UserService())->getLoginInfoByToken($request->header('token'));

        Work::where('work_date', $request['date'])
            ->where('work_task_id', $request['task_id'])
            ->delete();

        $works = $request['works'];
        foreach ($works as $work) {
            $newWork = new Work;
            $newWork['work_date'] = $request['date'];
            $newWork['work_room_id'] = $loginInfo['user_room_id'];
            $newWork['work_task_id'] = $request['task_id'];
            $newWork['work_user_id'] = $work['work_user_id'];
            $newWork['work_minute'] = $work['work_minute'];
            $newWork->save();
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
