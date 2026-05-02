<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * movie_id  → lihat MoviesTableSeeder
 * director_id → lihat DirectorsTableSeeder
 *
 * Movie ID  | Judul
 * ----------|----------------------------------------------
 *  1        | Inception
 *  2        | The Dark Knight
 *  3        | Interstellar
 *  4        | Parasite
 *  5        | Dune
 *  6        | Avengers: Endgame
 *  7        | Spider-Man: No Way Home
 *  8        | Get Out
 *  9        | Joker
 * 10        | Blade Runner 2049
 * 11        | Pulp Fiction
 * 12        | The Lord of the Rings: The Fellowship of the Ring
 *
 * Director ID | Nama
 * -----------|-----------------
 *  1         | Christopher Nolan
 *  2         | Bong Joon-ho
 *  3         | Denis Villeneuve
 *  4         | Anthony Russo
 *  5         | Joe Russo
 *  6         | Jon Watts
 *  7         | Jordan Peele
 *  8         | Todd Phillips
 *  9         | Quentin Tarantino
 * 10         | Peter Jackson
 */
class MovieDirectorsTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('movie_directors')->delete();

        DB::table('movie_directors')->insert([
            // Inception — Christopher Nolan
            ['movie_id' =>  1, 'director_id' => 1],
            // The Dark Knight — Christopher Nolan
            ['movie_id' =>  2, 'director_id' => 1],
            // Interstellar — Christopher Nolan
            ['movie_id' =>  3, 'director_id' => 1],
            // Parasite — Bong Joon-ho
            ['movie_id' =>  4, 'director_id' => 2],
            // Dune — Denis Villeneuve
            ['movie_id' =>  5, 'director_id' => 3],
            // Avengers: Endgame — Anthony & Joe Russo
            ['movie_id' =>  6, 'director_id' => 4],
            ['movie_id' =>  6, 'director_id' => 5],
            // Spider-Man: No Way Home — Jon Watts
            ['movie_id' =>  7, 'director_id' => 6],
            // Get Out — Jordan Peele
            ['movie_id' =>  8, 'director_id' => 7],
            // Joker — Todd Phillips
            ['movie_id' =>  9, 'director_id' => 8],
            // Blade Runner 2049 — Denis Villeneuve
            ['movie_id' => 10, 'director_id' => 3],
            // Pulp Fiction — Quentin Tarantino
            ['movie_id' => 11, 'director_id' => 9],
            // The Lord of the Rings: The Fellowship of the Ring — Peter Jackson
            ['movie_id' => 12, 'director_id' => 10],
            // 500 Days of Summer — Marc Webb
            ['movie_id' => 13, 'director_id' => 11],
            // Venom: Let There Be Carnage — Andy Serkis
            ['movie_id' => 14, 'director_id' => 12],
            // Iron Man 3 — Shane Black
            ['movie_id' => 15, 'director_id' => 13],
            // Kimi no Na wa. — Makoto Shinkai
            ['movie_id' => 16, 'director_id' => 14],
        ]);
    }
}