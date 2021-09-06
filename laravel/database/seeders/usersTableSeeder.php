<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class usersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
          [
            'id' => 1,
            'name' => '今野龍之介',
            'email' => 'yusan@gmail.com',
            'password' => 'password',
            'user_img' => 'https://picsum.photos/500/300?image=30',
            'token' => '1'.Str::random(100),
            'user_room_id' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
          ],
          [
            'id' => 2,
            'name' => '今野琴未',
            'email' => 'kottan@gmail.com',
            'password' => 'password',
            'user_img' => 'https://picsum.photos/500/300?image=40',
            'token' => '2'.Str::random(100),
            'user_room_id' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
          ],
      ]);
    }
}
