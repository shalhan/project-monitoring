<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => "Admin",
            'email' => 'admin@gmail.com',
            'password' => bcrypt('123456'),
            'type_id' => 1
        ]);
        DB::table('users')->insert([
            'name' => "Lecture",
            'email' => 'lecture@gmail.com',
            'password' => bcrypt('123456'),
            'type_id' => 2
        ]);
        for($i = 1; $i<=5; $i++) {
            DB::table('users')->insert([
                'name' => "Lecture" . $i,
                'email' => 'lecture'. $i.'@gmail.com',
                'password' => bcrypt('123456'),
                'type_id' => 2
            ]);
        }
    }
}
