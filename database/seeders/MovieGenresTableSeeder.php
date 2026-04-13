<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MovieGenresTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        DB::table('movie_genres')->delete();
        
        DB::table('movie_genres')->insert(array (
            0 => 
            array (
                'movie_id' => 2,
                'genre_id' => 1,
            ),
            1 => 
            array (
                'movie_id' => 3,
                'genre_id' => 1,
            ),
            2 => 
            array (
                'movie_id' => 7,
                'genre_id' => 1,
            ),
            3 => 
            array (
                'movie_id' => 8,
                'genre_id' => 1,
            ),
            4 => 
            array (
                'movie_id' => 1,
                'genre_id' => 2,
            ),
            5 => 
            array (
                'movie_id' => 3,
                'genre_id' => 2,
            ),
            6 => 
            array (
                'movie_id' => 4,
                'genre_id' => 2,
            ),
            7 => 
            array (
                'movie_id' => 5,
                'genre_id' => 2,
            ),
            8 => 
            array (
                'movie_id' => 6,
                'genre_id' => 2,
            ),
            9 => 
            array (
                'movie_id' => 9,
                'genre_id' => 2,
            ),
            10 => 
            array (
                'movie_id' => 10,
                'genre_id' => 2,
            ),
            11 => 
            array (
                'movie_id' => 9,
                'genre_id' => 3,
            ),
            12 => 
            array (
                'movie_id' => 10,
                'genre_id' => 4,
            ),
            13 => 
            array (
                'movie_id' => 1,
                'genre_id' => 5,
            ),
            14 => 
            array (
                'movie_id' => 2,
                'genre_id' => 5,
            ),
            15 => 
            array (
                'movie_id' => 4,
                'genre_id' => 5,
            ),
            16 => 
            array (
                'movie_id' => 5,
                'genre_id' => 5,
            ),
            17 => 
            array (
                'movie_id' => 6,
                'genre_id' => 5,
            ),
            18 => 
            array (
                'movie_id' => 7,
                'genre_id' => 5,
            ),
            19 => 
            array (
                'movie_id' => 8,
                'genre_id' => 5,
            ),
        ));
        
        
    }
}