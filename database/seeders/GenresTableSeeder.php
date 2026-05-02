<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GenresTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('genres')->delete();

        DB::table('genres')->insert([
            ['id' =>  1, 'name' => 'Action'],
            ['id' =>  2, 'name' => 'Adventure'],
            ['id' =>  3, 'name' => 'Comedy'],
            ['id' =>  4, 'name' => 'Crime'],
            ['id' =>  5, 'name' => 'Drama'],
            ['id' =>  6, 'name' => 'Fantasy'],
            ['id' =>  7, 'name' => 'Horror'],
            ['id' =>  8, 'name' => 'Mystery'],
            ['id' =>  9, 'name' => 'Romance'],
            ['id' => 10, 'name' => 'Sci-Fi'],
            ['id' => 11, 'name' => 'Thriller'],
            ['id' => 12, 'name' => 'Animation'],
        ]);
    }
}