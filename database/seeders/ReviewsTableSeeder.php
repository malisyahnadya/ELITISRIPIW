<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReviewsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        DB::table('reviews')->delete();
        
        DB::table('reviews')->insert(array (
            0 => 
            array (
                'id' => 1,
                'user_id' => 2,
                'movie_id' => 1,
                'review_text' => 'Mind-bending concept with a tight execution. The dream layers are still unmatched.',
                'created_at' => '2026-04-05 11:23:44',
                'updated_at' => '2026-04-05 11:23:44',
            ),
            1 => 
            array (
                'id' => 2,
                'user_id' => 2,
                'movie_id' => 3,
                'review_text' => 'Peak superhero movie. Joker feels genuinely chaotic, and the pacing never drags.',
                'created_at' => '2026-04-05 11:23:44',
                'updated_at' => '2026-04-05 11:23:44',
            ),
            2 => 
            array (
                'id' => 3,
                'user_id' => 3,
                'movie_id' => 2,
                'review_text' => 'A classic that aged well. The philosophy is fun, and the action still hits.',
                'created_at' => '2026-04-05 11:23:44',
                'updated_at' => '2026-04-05 11:23:44',
            ),
            3 => 
            array (
                'id' => 4,
                'user_id' => 3,
                'movie_id' => 10,
                'review_text' => 'Uncomfortable in the best way. Great build-up and payoff.',
                'created_at' => '2026-04-05 11:23:44',
                'updated_at' => '2026-04-05 11:23:44',
            ),
            4 => 
            array (
                'id' => 5,
                'user_id' => 4,
                'movie_id' => 7,
                'review_text' => 'Pure fan service but surprisingly emotional. The multiverse chaos is entertaining.',
                'created_at' => '2026-04-05 11:23:44',
                'updated_at' => '2026-04-05 11:23:44',
            ),
            5 => 
            array (
                'id' => 6,
                'user_id' => 4,
                'movie_id' => 9,
                'review_text' => 'Sharp satire with brilliant tension. The class commentary lands hard.',
                'created_at' => '2026-04-05 11:23:44',
                'updated_at' => '2026-04-05 11:23:44',
            ),
            6 => 
            array (
                'id' => 7,
                'user_id' => 3,
                'movie_id' => 5,
                'review_text' => 'Atmosphere and cinematography are insane. Slow burn, but worth it.',
                'created_at' => '2026-04-05 11:23:44',
                'updated_at' => '2026-04-05 11:23:44',
            ),
            7 => 
            array (
                'id' => 8,
                'user_id' => 2,
                'movie_id' => 4,
                'review_text' => 'Ambitious, emotional, and visually stunning. The score is a masterpiece.',
                'created_at' => '2026-04-05 11:23:44',
                'updated_at' => '2026-04-05 11:23:44',
            ),
        ));
        
        
    }
}