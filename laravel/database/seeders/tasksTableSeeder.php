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
            'task_name' => 'お風呂洗い',
            'task_status' => null,
            'task_default_minute' => 5,
            'task_is_everyday' => '1',
          ],
          [
            'task_room_id' => 1,
            'task_name' => '洗濯物の片付け',
            'task_status' => null,
            'task_default_minute' => 5,
            'task_is_everyday' => '1',
          ],
          [
            'task_room_id' => 1,
            'task_name' => '夕飯後の洗い物',
            'task_status' => null,
            'task_default_minute' => 15,
            'task_is_everyday' => '1',
          ],
          [
            'task_room_id' => 1,
            'task_name' => '洗濯機の埃取り',
            'task_status' => null,
            'task_default_minute' => 5,
            'task_is_everyday' => 1,
          ],
          [
            'task_room_id' => 1,
            'task_name' => '燃えるゴミ出し',
            'task_status' => null,
            'task_default_minute' => 10,
            'task_is_everyday' => 1,
          ],
          [
            'task_room_id' => 1,
            'task_name' => '資源ごみ出し',
            'task_status' => null,
            'task_default_minute' => 5,
            'task_is_everyday' => 1,
          ],
          [
            'task_room_id' => 1,
            'task_name' => 'プラスチックゴミ出し',
            'task_status' => null,
            'task_default_minute' => 5,
            'task_is_everyday' => 1,
          ],
          [
            'task_room_id' => 1,
            'task_name' => '結婚式の招待状作成',
            'task_status' => 1,
            'task_default_minute' => 120,
            'task_is_everyday' => null,
          ],
          [
            'task_room_id' => 1,
            'task_name' => '結婚式の進行内容や演出の検討',
            'task_status' => 1,
            'task_default_minute' => 180,
            'task_is_everyday' => null,
          ],
          [
            'task_room_id' => 1,
            'task_name' => '引出物・引菓子の検討',
            'task_status' => 1,
            'task_default_minute' => 90,
            'task_is_everyday' => null,
          ],
          [
            'task_room_id' => 2,
            'task_name' => 'ことみのタスク',
            'task_status' => 1,
            'task_default_minute' => 90,
            'task_is_everyday' => 1,
          ],
          ]);
    }
}