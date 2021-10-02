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
            'token' => 'yusan@gmail.com'.Str::random(100),
            'user_room_id' => 1,
          ],
          [
            'id' => 2,
            'name' => '今野琴未',
            'email' => 'kottan@gmail.com',
            'password' => 'password',
            'user_img' => 'https://picsum.photos/500/300?image=40',
            'token' => 'kottan@gmail.com'.Str::random(100),
            'user_room_id' => 1,
          ],
          [
            'id' => 3,
            'name' => '田中浩',
            'email' => 'tanaka@gmail.com',
            'password' => 'password',
            'user_img' => 'https://picsum.photos/500/300?image=10',
            'token' => 'tanaka@gmail.com'.Str::random(100),
            'user_room_id' => 2,
          ],
          [
            'id' => 4,
            'name' => '山田隆',
            'email' => 'yamada@gmail.com',
            'password' => 'password',
            'user_img' => 'https://picsum.photos/500/300?image=11',
            'token' => 'yamada@gmail.com'.Str::random(100),
            'user_room_id' => 2,
          ],
      ]);
    }
}
