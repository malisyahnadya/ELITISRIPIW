<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Genre IDs → lihat GenresTableSeeder
 *
 *  1 = Action    |  2 = Adventure |  3 = Comedy   |  4 = Crime
 *  5 = Drama     |  6 = Fantasy   |  7 = Horror   |  8 = Mystery
 *  9 = Romance   | 10 = Sci-Fi    | 11 = Thriller
 */
class MovieGenresTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('movie_genres')->delete();

        DB::table('movie_genres')->insert([
            // Inception (1) → Action, Adventure, Sci-Fi, Thriller
            ['movie_id' =>  1, 'genre_id' =>  1],
            ['movie_id' =>  1, 'genre_id' =>  2],
            ['movie_id' =>  1, 'genre_id' => 10],
            ['movie_id' =>  1, 'genre_id' => 11],

            // The Dark Knight (2) → Action, Crime, Drama, Thriller
            ['movie_id' =>  2, 'genre_id' =>  1],
            ['movie_id' =>  2, 'genre_id' =>  4],
            ['movie_id' =>  2, 'genre_id' =>  5],
            ['movie_id' =>  2, 'genre_id' => 11],

            // Interstellar (3) → Adventure, Drama, Sci-Fi
            ['movie_id' =>  3, 'genre_id' =>  2],
            ['movie_id' =>  3, 'genre_id' =>  5],
            ['movie_id' =>  3, 'genre_id' => 10],

            // Parasite (4) → Comedy, Crime, Drama, Thriller
            ['movie_id' =>  4, 'genre_id' =>  3],
            ['movie_id' =>  4, 'genre_id' =>  4],
            ['movie_id' =>  4, 'genre_id' =>  5],
            ['movie_id' =>  4, 'genre_id' => 11],

            // Dune (5) → Action, Adventure, Drama, Sci-Fi
            ['movie_id' =>  5, 'genre_id' =>  1],
            ['movie_id' =>  5, 'genre_id' =>  2],
            ['movie_id' =>  5, 'genre_id' =>  5],
            ['movie_id' =>  5, 'genre_id' => 10],

            // Avengers: Endgame (6) → Action, Adventure, Drama, Sci-Fi
            ['movie_id' =>  6, 'genre_id' =>  1],
            ['movie_id' =>  6, 'genre_id' =>  2],
            ['movie_id' =>  6, 'genre_id' =>  5],
            ['movie_id' =>  6, 'genre_id' => 10],

            // Spider-Man: No Way Home (7) → Action, Adventure, Fantasy, Sci-Fi
            ['movie_id' =>  7, 'genre_id' =>  1],
            ['movie_id' =>  7, 'genre_id' =>  2],
            ['movie_id' =>  7, 'genre_id' =>  6],
            ['movie_id' =>  7, 'genre_id' => 10],

            // Get Out (8) → Horror, Mystery, Thriller
            ['movie_id' =>  8, 'genre_id' =>  7],
            ['movie_id' =>  8, 'genre_id' =>  8],
            ['movie_id' =>  8, 'genre_id' => 11],

            // Joker (9) → Crime, Drama, Thriller
            ['movie_id' =>  9, 'genre_id' =>  4],
            ['movie_id' =>  9, 'genre_id' =>  5],
            ['movie_id' =>  9, 'genre_id' => 11],

            // Blade Runner 2049 (10) → Action, Drama, Mystery, Sci-Fi, Thriller
            ['movie_id' => 10, 'genre_id' =>  1],
            ['movie_id' => 10, 'genre_id' =>  5],
            ['movie_id' => 10, 'genre_id' =>  8],
            ['movie_id' => 10, 'genre_id' => 10],
            ['movie_id' => 10, 'genre_id' => 11],

            // Pulp Fiction (11) → Crime, Drama, Thriller
            ['movie_id' => 11, 'genre_id' =>  4],
            ['movie_id' => 11, 'genre_id' =>  5],
            ['movie_id' => 11, 'genre_id' => 11],

            // The Lord of the Rings: The Fellowship of the Ring (12) → Action, Adventure, Drama, Fantasy
            ['movie_id' => 12, 'genre_id' =>  1],
            ['movie_id' => 12, 'genre_id' =>  2],
            ['movie_id' => 12, 'genre_id' =>  5],
            ['movie_id' => 12, 'genre_id' =>  6],

            // 500 Days of Summer (13) → Comedy, Drama, Romance
            ['movie_id' => 13, 'genre_id' =>  3],
            ['movie_id' => 13, 'genre_id' =>  5],
            ['movie_id' => 13, 'genre_id' =>  9],

            // Venom: Let There Be Carnage (14) → Action, Sci-Fi, Thriller
            ['movie_id' => 14, 'genre_id' =>  1],
            ['movie_id' => 14, 'genre_id' => 10],
            ['movie_id' => 14, 'genre_id' => 11],

            // Iron Man 3 (15) → Action, Adventure, Sci-Fi
            ['movie_id' => 15, 'genre_id' =>  1],
            ['movie_id' => 15, 'genre_id' =>  2],
            ['movie_id' => 15, 'genre_id' => 10],

            // Kimi no Na wa. (16) → Animation, Drama, Fantasy, Romance
            ['movie_id' => 16, 'genre_id' => 12],
            ['movie_id' => 16, 'genre_id' =>  5],
            ['movie_id' => 16, 'genre_id' =>  6],
            ['movie_id' => 16, 'genre_id' =>  9],
        ]);
    }
}