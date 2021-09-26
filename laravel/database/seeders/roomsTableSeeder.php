<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class roomsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       DB::table('rooms')->insert([
          [
            'room_id' => 1,
            'room_name' => '今野家',
            'room_token' => date('Y-m-d H:i:s').Str::random(100),
            'room_img' => 'https://picsum.photos/500/300?image=1',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
          ],
          [
            'room_id' => 2,
            'room_name' => '田中家',
            'room_token' => date('Y-m-d H:i:s').Str::random(100),
            'room_img' => 'https://picsum.photos/500/300?image=2',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
          ],
          [
            'room_id' => 3,
            'room_name' => '山田家',
            'room_token' => date('Y-m-d H:i:s').Str::random(100),
            'room_img' => 'https://picsum.photos/500/300?image=3',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
          ],
       ]);
    }
}
