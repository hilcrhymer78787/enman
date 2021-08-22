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
}
