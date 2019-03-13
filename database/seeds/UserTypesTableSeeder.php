<?php

use Illuminate\Database\Seeder;

class UserTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('user_types')->insert([
            'id' => 1,
            'name' => 'Admin',
        ]); 
        DB::table('user_types')->insert([
            'id' => 2,
            'name' => 'Lecture'
        ]);        
        DB::table('user_types')->insert([
            'id' => 3,
            'name' => 'Student'
        ]);       
    }
}
