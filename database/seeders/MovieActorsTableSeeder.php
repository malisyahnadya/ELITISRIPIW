<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MovieActorsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        DB::table('movie_actors')->delete();
        
        DB::table('movie_actors')->insert(array (
            0 => 
            array (
                'movie_id' => 1,
                'actor_id' => 1,
                'role_name' => 'Cobb',
            ),
            1 => 
            array (
                'movie_id' => 1,
                'actor_id' => 2,
                'role_name' => 'Arthur',
            ),
            2 => 
            array (
                'movie_id' => 1,
                'actor_id' => 3,
                'role_name' => 'Ariadne',
            ),
            3 => 
            array (
                'movie_id' => 2,
                'actor_id' => 4,
                'role_name' => 'Neo',
            ),
            4 => 
            array (
                'movie_id' => 2,
                'actor_id' => 5,
                'role_name' => 'Trinity',
            ),
            5 => 
            array (
                'movie_id' => 2,
                'actor_id' => 6,
                'role_name' => 'Morpheus',
            ),
            6 => 
            array (
                'movie_id' => 3,
                'actor_id' => 7,
                'role_name' => 'Bruce Wayne / Batman',
            ),
            7 => 
            array (
                'movie_id' => 3,
                'actor_id' => 8,
                'role_name' => 'Joker',
            ),
            8 => 
            array (
                'movie_id' => 3,
                'actor_id' => 9,
                'role_name' => 'Harvey Dent',
            ),
            9 => 
            array (
                'movie_id' => 4,
                'actor_id' => 10,
                'role_name' => 'Cooper',
            ),
            10 => 
            array (
                'movie_id' => 4,
                'actor_id' => 11,
                'role_name' => 'Brand',
            ),
            11 => 
            array (
                'movie_id' => 5,
                'actor_id' => 12,
                'role_name' => 'K',
            ),
            12 => 
            array (
                'movie_id' => 5,
                'actor_id' => 13,
                'role_name' => 'Deckard',
            ),
            13 => 
            array (
                'movie_id' => 6,
                'actor_id' => 14,
                'role_name' => 'Paul Atreides',
            ),
            14 => 
            array (
                'movie_id' => 6,
                'actor_id' => 15,
                'role_name' => 'Chani',
            ),
            15 => 
            array (
                'movie_id' => 7,
                'actor_id' => 15,
                'role_name' => 'MJ',
            ),
            16 => 
            array (
                'movie_id' => 7,
                'actor_id' => 16,
                'role_name' => 'Peter Parker',
            ),
            17 => 
            array (
                'movie_id' => 7,
                'actor_id' => 17,
                'role_name' => 'Doctor Strange',
            ),
            18 => 
            array (
                'movie_id' => 8,
                'actor_id' => 18,
                'role_name' => 'Tony Stark / Iron Man',
            ),
            19 => 
            array (
                'movie_id' => 8,
                'actor_id' => 19,
                'role_name' => 'Steve Rogers / Captain America',
            ),
            20 => 
            array (
                'movie_id' => 8,
                'actor_id' => 20,
                'role_name' => 'Natasha Romanoff / Black Widow',
            ),
            21 => 
            array (
                'movie_id' => 9,
                'actor_id' => 21,
                'role_name' => 'Kim Ki-taek',
            ),
            22 => 
            array (
                'movie_id' => 9,
                'actor_id' => 22,
                'role_name' => 'Park Dong-ik',
            ),
            23 => 
            array (
                'movie_id' => 9,
                'actor_id' => 23,
                'role_name' => 'Yeon-kyo',
            ),
            24 => 
            array (
                'movie_id' => 10,
                'actor_id' => 24,
                'role_name' => 'Chris Washington',
            ),
            25 => 
            array (
                'movie_id' => 10,
                'actor_id' => 25,
                'role_name' => 'Rose Armitage',
            ),
        ));
        
        
    }
}