<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DirectorsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        DB::table('directors')->delete();
        
        DB::table('directors')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Christopher Nolan',
                'photo_path' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Lana Wachowski',
                'photo_path' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Lilly Wachowski',
                'photo_path' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'Denis Villeneuve',
                'photo_path' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'Jon Watts',
                'photo_path' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
                'name' => 'Anthony Russo',
                'photo_path' => NULL,
            ),
            6 => 
            array (
                'id' => 7,
                'name' => 'Joe Russo',
                'photo_path' => NULL,
            ),
            7 => 
            array (
                'id' => 8,
                'name' => 'Bong Joon-ho',
                'photo_path' => NULL,
            ),
            8 => 
            array (
                'id' => 9,
                'name' => 'Jordan Peele',
                'photo_path' => NULL,
            ),
        ));
        
        
    }
}