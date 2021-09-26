<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invitation;

class InvitationController extends Controller
{
    public function read()
    {
        $data = Invitation::get();
        return $data;
    }
}
