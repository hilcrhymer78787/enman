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
        for ($i = 1; $i  < 200; $i++) {
            $day = $i - 100;
            DB::table('works')->insert([
                [
                    'work_room_id' => 1,
                    'work_date' => date('Y-m-d', strtotime("-${day} day")),
                    'work_task_id' => 1,
                    'work_user_id' => 1,
                    'work_minute' => 15,
                ],
            ]);
            DB::table('works')->insert([
                [
                    'work_room_id' => 1,
                    'work_date' => date('Y-m-d', strtotime("-${day} day")),
                    'work_task_id' => $i % 2 + 3,
                    'work_user_id' => 3,
                    'work_minute' => 15,
                ],
            ]);
            DB::table('works')->insert([
                [
                    'work_room_id' => 1,
                    'work_date' => date('Y-m-d', strtotime("-${day} day")),
                    'work_task_id' => 5,
                    'work_user_id' => $i % 3 + 1,
                    'work_minute' => 10,
                ],
            ]);
        }
        for ($i = 1; $i  < 200; $i = $i + 2) {
            $day = $i - 100;
            DB::table('works')->insert([
                [
                    'work_room_id' => 1,
                    'work_date' => date('Y-m-d', strtotime("-${day} day")),
                    'work_task_id' => 2,
                    'work_user_id' => 2,
                    'work_minute' => 30,
                ],
            ]);
        }
    }
}
