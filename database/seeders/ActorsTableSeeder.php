<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ActorsTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('actors')->delete();

        DB::table('actors')->insert([
            // Inception
            ['id' =>  1, 'name' => 'Leonardo DiCaprio',    'photo_path' => null],
            ['id' =>  2, 'name' => 'Joseph Gordon-Levitt', 'photo_path' => null],
            ['id' =>  3, 'name' => 'Elliot Page',          'photo_path' => null],
            ['id' =>  4, 'name' => 'Tom Hardy',            'photo_path' => null],
            // The Dark Knight
            ['id' =>  5, 'name' => 'Christian Bale',       'photo_path' => null],
            ['id' =>  6, 'name' => 'Heath Ledger',         'photo_path' => null],
            ['id' =>  7, 'name' => 'Aaron Eckhart',        'photo_path' => null],
            ['id' =>  8, 'name' => 'Maggie Gyllenhaal',    'photo_path' => null],
            // Interstellar
            ['id' =>  9, 'name' => 'Matthew McConaughey',  'photo_path' => null],
            ['id' => 10, 'name' => 'Anne Hathaway',        'photo_path' => null],
            ['id' => 11, 'name' => 'Jessica Chastain',     'photo_path' => null],
            // Parasite
            ['id' => 12, 'name' => 'Song Kang-ho',         'photo_path' => null],
            ['id' => 13, 'name' => 'Choi Woo-shik',        'photo_path' => null],
            ['id' => 14, 'name' => 'Park So-dam',          'photo_path' => null],
            ['id' => 15, 'name' => 'Lee Sun-kyun',         'photo_path' => null],
            // Dune
            ['id' => 16, 'name' => 'Timothée Chalamet',    'photo_path' => null],
            ['id' => 17, 'name' => 'Zendaya',              'photo_path' => null],
            ['id' => 18, 'name' => 'Rebecca Ferguson',     'photo_path' => null],
            ['id' => 19, 'name' => 'Oscar Isaac',          'photo_path' => null],
            // Avengers: Endgame
            ['id' => 20, 'name' => 'Robert Downey Jr.',    'photo_path' => null],
            ['id' => 21, 'name' => 'Chris Evans',          'photo_path' => null],
            ['id' => 22, 'name' => 'Scarlett Johansson',   'photo_path' => null],
            ['id' => 23, 'name' => 'Mark Ruffalo',         'photo_path' => null],
            // Spider-Man: No Way Home
            ['id' => 24, 'name' => 'Tom Holland',          'photo_path' => null],
            ['id' => 25, 'name' => 'Tobey Maguire',        'photo_path' => null],
            ['id' => 26, 'name' => 'Andrew Garfield',      'photo_path' => null],
            ['id' => 27, 'name' => 'Benedict Cumberbatch', 'photo_path' => null],
            // Get Out
            ['id' => 28, 'name' => 'Daniel Kaluuya',       'photo_path' => null],
            ['id' => 29, 'name' => 'Allison Williams',     'photo_path' => null],
            // Joker
            ['id' => 30, 'name' => 'Joaquin Phoenix',      'photo_path' => null],
            ['id' => 31, 'name' => 'Robert De Niro',       'photo_path' => null],
            ['id' => 32, 'name' => 'Zazie Beetz',          'photo_path' => null],
            // Blade Runner 2049
            ['id' => 33, 'name' => 'Ryan Gosling',         'photo_path' => null],
            ['id' => 34, 'name' => 'Harrison Ford',        'photo_path' => null],
            ['id' => 35, 'name' => 'Ana de Armas',         'photo_path' => null],
            // Pulp Fiction
            ['id' => 36, 'name' => 'John Travolta',        'photo_path' => null],
            ['id' => 37, 'name' => 'Samuel L. Jackson',    'photo_path' => null],
            ['id' => 38, 'name' => 'Uma Thurman',          'photo_path' => null],
            // The Lord of the Rings
            ['id' => 39, 'name' => 'Elijah Wood',          'photo_path' => null],
            ['id' => 40, 'name' => 'Ian McKellen',         'photo_path' => null],
            ['id' => 41, 'name' => 'Viggo Mortensen',      'photo_path' => null],
            // 500 Days of Summer
            // Joseph Gordon-Levitt sudah ada di ID 2 → dipakai langsung di relasi
            ['id' => 42, 'name' => 'Zooey Deschanel',      'photo_path' => null],
            // Venom: Let There Be Carnage
            // Tom Hardy sudah ada di ID 4 → dipakai langsung di relasi
            ['id' => 43, 'name' => 'Woody Harrelson',      'photo_path' => null],
            ['id' => 44, 'name' => 'Michelle Williams',    'photo_path' => null],
            // Iron Man 3
            // Robert Downey Jr. sudah ada di ID 20 → dipakai langsung di relasi
            ['id' => 45, 'name' => 'Gwyneth Paltrow',      'photo_path' => null],
            ['id' => 46, 'name' => 'Don Cheadle',          'photo_path' => null],
            ['id' => 47, 'name' => 'Guy Pearce',           'photo_path' => null],
            // Kimi no Na wa. (voice actors)
            ['id' => 48, 'name' => 'Ryunosuke Kamiki',     'photo_path' => null],
            ['id' => 49, 'name' => 'Mone Kamishiraishi',   'photo_path' => null],
        ]);
    }
}