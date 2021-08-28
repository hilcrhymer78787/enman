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
            'task_id' => '1',
            'task_room_id' => '1',
            'task_name' => 'お風呂洗い',
            'task_status' => null,
            'task_default_minute' => '5',
            'point_per_minute' => '5',
            'is_everyday' => '1',
          ],
          [
            'task_id' => '2',
            'task_room_id' => '1',
            'task_name' => '洗濯物の片付け',
            'task_status' => null,
            'task_default_minute' => '5',
            'point_per_minute' => '7',
            'is_everyday' => '1',
          ],
          [
            'task_id' => '3',
            'task_room_id' => '1',
            'task_name' => '夕飯後の洗い物',
            'task_status' => null,
            'task_default_minute' => '15',
            'point_per_minute' => '20',
            'is_everyday' => '1',
          ],
          [
            'task_id' => '4',
            'task_room_id' => '1',
            'task_name' => '洗濯機の埃取り',
            'task_status' => null,
            'task_default_minute' => '5',
            'point_per_minute' => '4',
            'is_everyday' => null,
          ],
          [
            'task_id' => '5',
            'task_room_id' => '1',
            'task_name' => '燃えるゴミ出し',
            'task_status' => null,
            'task_default_minute' => '10',
            'point_per_minute' => '14',
            'is_everyday' => null,
          ],
          [
            'task_id' => '6',
            'task_room_id' => '1',
            'task_name' => '資源ごみ出し',
            'task_status' => null,
            'task_default_minute' => '5',
            'point_per_minute' => '5',
            'is_everyday' => null,
          ],
          [
            'task_id' => '7',
            'task_room_id' => '1',
            'task_name' => 'プラスチックゴミ出し',
            'task_status' => null,
            'task_default_minute' => '5',
            'point_per_minute' => '5',
            'is_everyday' => null,
          ],
          [
            'task_id' => '8',
            'task_room_id' => '1',
            'task_name' => '結婚式の招待状作成',
            'task_status' => '1',
            'task_default_minute' => '120',
            'point_per_minute' => '120',
            'is_everyday' => null,
          ],
          [
            'task_id' => '9',
            'task_room_id' => '1',
            'task_name' => '結婚式の進行内容や演出の検討',
            'task_status' => '1',
            'task_default_minute' => '180',
            'point_per_minute' => '200',
            'is_everyday' => null,
          ],
          [
            'task_id' => '10',
            'task_room_id' => '1',
            'task_name' => '引出物・引菓子の検討',
            'task_status' => '1',
            'task_default_minute' => '90',
            'point_per_minute' => '90',
            'is_everyday' => null,
          ],
          ]);
    }
}