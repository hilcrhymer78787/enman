<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;

class RoomController extends Controller
{
    public function read()
    {
        $data = Room::get();
        return $data;
    }
}
