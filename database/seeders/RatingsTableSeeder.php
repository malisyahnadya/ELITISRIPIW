<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RatingsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        DB::table('ratings')->delete();
        
        DB::table('ratings')->insert(array (
            0 => 
            array (
                'id' => 1,
                'user_id' => 2,
                'movie_id' => 1,
                'score' => 4,
                'created_at' => '2026-04-05 11:23:44',
            ),
            1 => 
            array (
                'id' => 2,
                'user_id' => 2,
                'movie_id' => 3,
                'score' => 5,
                'created_at' => '2026-04-05 11:23:44',
            ),
            2 => 
            array (
                'id' => 3,
                'user_id' => 2,
                'movie_id' => 8,
                'score' => 4,
                'created_at' => '2026-04-05 11:23:44',
            ),
            3 => 
            array (
                'id' => 4,
                'user_id' => 3,
                'movie_id' => 2,
                'score' => 5,
                'created_at' => '2026-04-05 11:23:44',
            ),
            4 => 
            array (
                'id' => 5,
                'user_id' => 3,
                'movie_id' => 5,
                'score' => 5,
                'created_at' => '2026-04-05 11:23:44',
            ),
            5 => 
            array (
                'id' => 6,
                'user_id' => 3,
                'movie_id' => 10,
                'score' => 5,
                'created_at' => '2026-04-05 11:23:44',
            ),
            6 => 
            array (
                'id' => 7,
                'user_id' => 4,
                'movie_id' => 7,
                'score' => 5,
                'created_at' => '2026-04-05 11:23:44',
            ),
            7 => 
            array (
                'id' => 8,
                'user_id' => 4,
                'movie_id' => 9,
                'score' => 5,
                'created_at' => '2026-04-05 11:23:44',
            ),
        ));
        
        
    }
}