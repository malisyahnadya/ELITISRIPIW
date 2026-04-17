<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        DB::table('users')->delete();
        
        DB::table('users')->insert(array (
            0 => 
            array (
                'id' => 1,
                'username' => 'admin',
                'email' => 'admin@demo.com',
                'password' => Hash::make('admin1234'),
                'profile_photo' => NULL,
                'bio' => 'Site admin',
                'role' => 'admin',
            ),
            1 => 
            array (
                'id' => 2,
                'username' => 'amir',
                'email' => 'amir@demo.com',
                'password' => Hash::make('user1234'),
                'profile_photo' => NULL,
                'bio' => 'Movie enjoyer',
                'role' => 'user',
            )
            ),
        );
        
        
    }
}