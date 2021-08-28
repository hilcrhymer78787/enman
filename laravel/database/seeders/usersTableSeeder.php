<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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
            'id' => '1',
            'name' => 'yusan',
            'email' => 'yusan@gmail.com',
            'password' => 'password',
            'user_room_id' => '1',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
          ],
          [
            'id' => '2',
            'name' => 'kottan',
            'email' => 'kottan@gmail.com',
            'password' => 'password',
            'user_room_id' => '1',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
          ],
      ]);
    }
}