<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class worksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //  \App\Models\Work::factory(100)->create();
        DB::table('works')->insert([
            [
              'work_room_id' => 1,
              'work_date'=>date('Y-m-d', strtotime('+1 day')),
              'work_task_id' => 1,
              'work_user_id' => 1,
              'work_minute' => 5,
              'created_at' => date('Y-m-d H:i:s'),
              'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
              'work_room_id' => 1,
              'work_date'=>date("Y-m-d H:i:s"),
              'work_task_id' => 1,
              'work_user_id' => 1,
              'work_minute' => 10,
              'created_at' => date('Y-m-d H:i:s'),
              'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
              'work_room_id' => 1,
              'work_date'=>date("Y-m-d H:i:s"),
              'work_task_id' => 2,
              'work_user_id' => 2,
              'work_minute' => 10,
              'created_at' => date('Y-m-d H:i:s'),
              'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
              'work_room_id' => 1,
              'work_date'=>date("Y-m-d H:i:s"),
              'work_task_id' => 3,
              'work_user_id' => 1,
              'work_minute' => 5,
              'created_at' => date('Y-m-d H:i:s'),
              'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
              'work_room_id' => 1,
              'work_date'=>date("Y-m-d H:i:s"),
              'work_task_id' => 3,
              'work_user_id' => 2,
              'work_minute' => 10,
              'created_at' => date('Y-m-d H:i:s'),
              'updated_at' => date('Y-m-d H:i:s'),
            ],
        ]);
    }
}
