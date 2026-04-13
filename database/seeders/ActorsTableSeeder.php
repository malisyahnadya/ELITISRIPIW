<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ActorsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        DB::table('actors')->delete();
        
        DB::table('actors')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Leonardo DiCaprio',
                'photo_path' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Joseph Gordon-Levitt',
                'photo_path' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Elliot Page',
                'photo_path' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'Keanu Reeves',
                'photo_path' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'Carrie-Anne Moss',
                'photo_path' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
                'name' => 'Laurence Fishburne',
                'photo_path' => NULL,
            ),
            6 => 
            array (
                'id' => 7,
                'name' => 'Christian Bale',
                'photo_path' => NULL,
            ),
            7 => 
            array (
                'id' => 8,
                'name' => 'Heath Ledger',
                'photo_path' => NULL,
            ),
            8 => 
            array (
                'id' => 9,
                'name' => 'Aaron Eckhart',
                'photo_path' => NULL,
            ),
            9 => 
            array (
                'id' => 10,
                'name' => 'Matthew McConaughey',
                'photo_path' => NULL,
            ),
            10 => 
            array (
                'id' => 11,
                'name' => 'Anne Hathaway',
                'photo_path' => NULL,
            ),
            11 => 
            array (
                'id' => 12,
                'name' => 'Ryan Gosling',
                'photo_path' => NULL,
            ),
            12 => 
            array (
                'id' => 13,
                'name' => 'Harrison Ford',
                'photo_path' => NULL,
            ),
            13 => 
            array (
                'id' => 14,
                'name' => 'Timothée Chalamet',
                'photo_path' => NULL,
            ),
            14 => 
            array (
                'id' => 15,
                'name' => 'Zendaya',
                'photo_path' => NULL,
            ),
            15 => 
            array (
                'id' => 16,
                'name' => 'Tom Holland',
                'photo_path' => NULL,
            ),
            16 => 
            array (
                'id' => 17,
                'name' => 'Benedict Cumberbatch',
                'photo_path' => NULL,
            ),
            17 => 
            array (
                'id' => 18,
                'name' => 'Robert Downey Jr.',
                'photo_path' => NULL,
            ),
            18 => 
            array (
                'id' => 19,
                'name' => 'Chris Evans',
                'photo_path' => NULL,
            ),
            19 => 
            array (
                'id' => 20,
                'name' => 'Scarlett Johansson',
                'photo_path' => NULL,
            ),
            20 => 
            array (
                'id' => 21,
                'name' => 'Song Kang-ho',
                'photo_path' => NULL,
            ),
            21 => 
            array (
                'id' => 22,
                'name' => 'Lee Sun-kyun',
                'photo_path' => NULL,
            ),
            22 => 
            array (
                'id' => 23,
                'name' => 'Cho Yeo-jeong',
                'photo_path' => NULL,
            ),
            23 => 
            array (
                'id' => 24,
                'name' => 'Daniel Kaluuya',
                'photo_path' => NULL,
            ),
            24 => 
            array (
                'id' => 25,
                'name' => 'Allison Williams',
                'photo_path' => NULL,
            ),
        ));
        
        
    }
}