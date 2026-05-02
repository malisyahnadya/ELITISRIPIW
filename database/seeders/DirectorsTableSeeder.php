<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DirectorsTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('directors')->delete();

        DB::table('directors')->insert([
            ['id' => 1, 'name' => 'Christopher Nolan', 'photo_path' => null],
            ['id' => 2, 'name' => 'Bong Joon-ho',      'photo_path' => null],
            ['id' => 3, 'name' => 'Denis Villeneuve',  'photo_path' => null],
            ['id' => 4, 'name' => 'Anthony Russo',     'photo_path' => null],
            ['id' => 5, 'name' => 'Joe Russo',         'photo_path' => null],
            ['id' => 6, 'name' => 'Jon Watts',         'photo_path' => null],
            ['id' => 7, 'name' => 'Jordan Peele',      'photo_path' => null],
            ['id' => 8, 'name' => 'Todd Phillips',     'photo_path' => null],
            ['id' => 9, 'name' => 'Quentin Tarantino', 'photo_path' => null],
            ['id' => 10,'name' => 'Peter Jackson',     'photo_path' => null],
            ['id' => 11,'name' => 'Marc Webb',          'photo_path' => null], // 500 Days of Summer
            ['id' => 12,'name' => 'Andy Serkis',        'photo_path' => null], // Venom: Let There Be Carnage
            ['id' => 13,'name' => 'Shane Black',        'photo_path' => null], // Iron Man 3
            ['id' => 14,'name' => 'Makoto Shinkai',     'photo_path' => null], // Kimi no Na wa.
        ]);
    }
}