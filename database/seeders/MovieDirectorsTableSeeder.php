<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MovieDirectorsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        DB::table('movie_directors')->delete();
        
        DB::table('movie_directors')->insert(array (
            0 => 
            array (
                'movie_id' => 1,
                'director_id' => 1,
            ),
            1 => 
            array (
                'movie_id' => 3,
                'director_id' => 1,
            ),
            2 => 
            array (
                'movie_id' => 4,
                'director_id' => 1,
            ),
            3 => 
            array (
                'movie_id' => 2,
                'director_id' => 2,
            ),
            4 => 
            array (
                'movie_id' => 2,
                'director_id' => 3,
            ),
            5 => 
            array (
                'movie_id' => 5,
                'director_id' => 4,
            ),
            6 => 
            array (
                'movie_id' => 6,
                'director_id' => 4,
            ),
            7 => 
            array (
                'movie_id' => 7,
                'director_id' => 5,
            ),
            8 => 
            array (
                'movie_id' => 8,
                'director_id' => 6,
            ),
            9 => 
            array (
                'movie_id' => 8,
                'director_id' => 7,
            ),
            10 => 
            array (
                'movie_id' => 9,
                'director_id' => 8,
            ),
            11 => 
            array (
                'movie_id' => 10,
                'director_id' => 9,
            ),
        ));
        
        
    }
}