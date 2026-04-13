<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WatchlistsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        DB::table('watchlists')->delete();
        
        DB::table('watchlists')->insert(array (
            0 => 
            array (
                'id' => 1,
                'user_id' => 2,
                'movie_id' => 6,
                'status' => 'plan_to_watch',
                'created_at' => '2026-04-05 11:23:44',
                'updated_at' => '2026-04-05 11:23:44',
            ),
            1 => 
            array (
                'id' => 2,
                'user_id' => 2,
                'movie_id' => 5,
                'status' => 'watching',
                'created_at' => '2026-04-05 11:23:44',
                'updated_at' => '2026-04-05 11:23:44',
            ),
            2 => 
            array (
                'id' => 3,
                'user_id' => 3,
                'movie_id' => 4,
                'status' => 'completed',
                'created_at' => '2026-04-05 11:23:44',
                'updated_at' => '2026-04-05 11:23:44',
            ),
            3 => 
            array (
                'id' => 4,
                'user_id' => 3,
                'movie_id' => 9,
                'status' => 'watching',
                'created_at' => '2026-04-05 11:23:44',
                'updated_at' => '2026-04-05 11:23:44',
            ),
            4 => 
            array (
                'id' => 5,
                'user_id' => 4,
                'movie_id' => 1,
                'status' => 'completed',
                'created_at' => '2026-04-05 11:23:44',
                'updated_at' => '2026-04-05 11:23:44',
            ),
            5 => 
            array (
                'id' => 6,
                'user_id' => 4,
                'movie_id' => 10,
                'status' => 'plan_to_watch',
                'created_at' => '2026-04-05 11:23:44',
                'updated_at' => '2026-04-05 11:23:44',
            ),
        ));
        
        
    }
}