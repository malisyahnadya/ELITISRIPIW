<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GenresTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        DB::table('genres')->delete();
        
        DB::table('genres')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Action',
            ),
            1 => 
            array (
                'id' => 3,
                'name' => 'Comedy',
            ),
            2 => 
            array (
                'id' => 2,
                'name' => 'Drama',
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'Horror',
            ),
            4 => 
            array (
                'id' => 6,
                'name' => 'Romance',
            ),
            5 => 
            array (
                'id' => 5,
                'name' => 'Sci-Fi',
            ),
        ));
        
        
    }
}