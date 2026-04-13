<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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
                'password_hash' => '$2b$10$kaIiedKWQGdPEZERguD3zear/KJhG4N/tYyFUlhsEMIpMW3pWEWm6',
                'profile_photo' => NULL,
                'bio' => 'Site admin',
                'role' => 'admin',
                'created_at' => '2026-04-05 11:23:44',
                'updated_at' => '2026-04-05 11:23:44',
            ),
            1 => 
            array (
                'id' => 2,
                'username' => 'amir',
                'email' => 'amir@demo.com',
                'password_hash' => '$2b$10$4N6p8H9CIFh1UMMCLxqcE.Ba15GMQ.UAtuNHZEQbcP31h9Fc.bmea',
                'profile_photo' => NULL,
                'bio' => 'Movie enjoyer',
                'role' => 'user',
                'created_at' => '2026-04-05 11:23:44',
                'updated_at' => '2026-04-05 11:23:44',
            ),
            2 => 
            array (
                'id' => 3,
                'username' => 'sinta',
                'email' => 'sinta@demo.com',
                'password_hash' => '$2b$10$4N6p8H9CIFh1UMMCLxqcE.Ba15GMQ.UAtuNHZEQbcP31h9Fc.bmea',
                'profile_photo' => NULL,
                'bio' => 'Sci-fi & thriller',
                'role' => 'user',
                'created_at' => '2026-04-05 11:23:44',
                'updated_at' => '2026-04-05 11:23:44',
            ),
            3 => 
            array (
                'id' => 4,
                'username' => 'budi',
                'email' => 'budi@demo.com',
                'password_hash' => '$2b$10$4N6p8H9CIFh1UMMCLxqcE.Ba15GMQ.UAtuNHZEQbcP31h9Fc.bmea',
                'profile_photo' => NULL,
                'bio' => 'Action & drama',
                'role' => 'user',
                'created_at' => '2026-04-05 11:23:44',
                'updated_at' => '2026-04-05 11:23:44',
            ),
        ));
        
        
    }
}