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
            'name' => '織田信長',
            'email' => 'user1@gmail.com',
            'password' => 'password',
            'user_img' => 'https://picsum.photos/500/300?image=30',
            'token' => 'user1@gmail.com'.Str::random(100),
            'user_room_id' => 1,
          ],
          [
            'id' => 2,
            'name' => '豊臣秀吉',
            'email' => 'user2@gmail.com',
            'password' => 'password',
            'user_img' => 'https://picsum.photos/500/300?image=40',
            'token' => 'user2@gmail.com'.Str::random(100),
            'user_room_id' => 1,
          ],
          [
            'id' => 3,
            'name' => '徳川家康',
            'email' => 'user3@gmail.com',
            'password' => 'password',
            'user_img' => 'https://picsum.photos/500/300?image=10',
            'token' => 'user3@gmail.com'.Str::random(100),
            'user_room_id' => 2,
          ],
          [
            'id' => 4,
            'name' => '武田信玄',
            'email' => 'user4@gmail.com',
            'password' => 'password',
            'user_img' => 'https://picsum.photos/500/300?image=11',
            'token' => 'user4@gmail.com'.Str::random(100),
            'user_room_id' => 2,
          ],
      ]);
    }
}
