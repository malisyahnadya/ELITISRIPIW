<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReviewLikesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        DB::table('review_likes')->delete();
        
        DB::table('review_likes')->insert(array (
            0 => 
            array (
                'id' => 1,
                'user_id' => 3,
                'review_id' => 1,
                'created_at' => '2026-04-05 11:23:45',
            ),
            1 => 
            array (
                'id' => 2,
                'user_id' => 4,
                'review_id' => 1,
                'created_at' => '2026-04-05 11:23:45',
            ),
            2 => 
            array (
                'id' => 3,
                'user_id' => 2,
                'review_id' => 3,
                'created_at' => '2026-04-05 11:23:45',
            ),
            3 => 
            array (
                'id' => 4,
                'user_id' => 2,
                'review_id' => 6,
                'created_at' => '2026-04-05 11:23:45',
            ),
            4 => 
            array (
                'id' => 5,
                'user_id' => 3,
                'review_id' => 2,
                'created_at' => '2026-04-05 11:23:45',
            ),
            5 => 
            array (
                'id' => 6,
                'user_id' => 4,
                'review_id' => 4,
                'created_at' => '2026-04-05 11:23:45',
            ),
        ));
        
        
    }
}