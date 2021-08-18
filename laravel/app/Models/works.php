<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class works extends Model
{
    use HasFactory;

    protected $fillable = [
      'work_room_id',
      'work_task_id',
      'work_user_id',
      'work_room_id',
      'work_minute_id',
    ];
}
