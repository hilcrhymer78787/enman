<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;

class TaskController extends Controller
{
    public function read(Request $request)
    {
        $data = Task::get();
        $hoge = $request->input('token');
        return $data;
    }
}
