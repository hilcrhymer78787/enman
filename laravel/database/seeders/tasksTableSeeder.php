<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class tasksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tasks')->insert([
            [
                'task_room_id' => 1,
                'task_name' => '洗い物',
                'task_status' => null,
                'task_default_minute' => 15,
                'task_is_everyday' => '1',
                'task_sort_key' => 2
            ],
            [
                'task_room_id' => 1,
                'task_name' => '料理',
                'task_status' => null,
                'task_default_minute' => 30,
                'task_is_everyday' => '1',
                'task_sort_key' => 3
            ],
            [
                'task_room_id' => 1,
                'task_name' => '洗濯',
                'task_status' => null,
                'task_default_minute' => 15,
                'task_is_everyday' => '1',
                'task_sort_key' => 4
            ],
            [
                'task_room_id' => 1,
                'task_name' => '掃除',
                'task_status' => null,
                'task_default_minute' => 20,
                'task_is_everyday' => 1,
                'task_sort_key' => 5
            ],
            [
                'task_room_id' => 1,
                'task_name' => 'ゴミ出し',
                'task_status' => null,
                'task_default_minute' => 10,
                'task_is_everyday' => 1,
                'task_sort_key' => 6
            ],
            [
                'task_room_id' => 1,
                'task_name' => '買い出し',
                'task_status' => null,
                'task_default_minute' => 30,
                'task_is_everyday' => 1,
                'task_sort_key' => 7
            ],
            [
                'task_room_id' => 1,
                'task_name' => 'その他',
                'task_status' => null,
                'task_default_minute' => 5,
                'task_is_everyday' => 1,
                'task_sort_key' => 8
            ],
            [
                'task_room_id' => 2,
                'task_name' => '掃除',
                'task_status' => 1,
                'task_default_minute' => 20,
                'task_is_everyday' => 1,
                'task_sort_key' => 1,
            ],
            [
                'task_room_id' => 3,
                'task_name' => '洗濯',
                'task_status' => 1,
                'task_default_minute' => 5,
                'task_is_everyday' => 1,
                'task_sort_key' => 1,
            ],
            [
                'task_room_id' => 3,
                'task_name' => '料理',
                'task_status' => 1,
                'task_default_minute' => 30,
                'task_is_everyday' => 1,
                'task_sort_key' => 2,
            ],
            [
                'task_room_id' => 3,
                'task_name' => '買い出し',
                'task_status' => 1,
                'task_default_minute' => 30,
                'task_is_everyday' => 1,
                'task_sort_key' => 3,
            ],
        ]);
    }
}
