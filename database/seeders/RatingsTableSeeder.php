<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

/**
 * User IDs:  2=amir  3=nisa  4=budi  5=raka  6=siska  7=dimas
 * Movie IDs: 1=Inception  2=The Dark Knight  3=Interstellar  4=Parasite
 *            5=Dune  6=Avengers: Endgame  7=Spider-Man: No Way Home
 *            8=Get Out  9=Joker  10=Blade Runner 2049
 *            11=Pulp Fiction  12=The Lord of the Rings
 *
 * Constraint: satu user hanya boleh satu rating per film (unique user_id + movie_id).
 * Score menggunakan skala yang ada di DB (sesuaikan jika app pakai 1-10 atau 1-5).
 */
class RatingsTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('ratings')->delete();

        $now = Carbon::now();

        DB::table('ratings')->insert([
            // amir (2)
            ['id' =>  1, 'user_id' => 2, 'movie_id' =>  1, 'score' => 5, 'created_at' => $now->copy()->subDays(30)],
            ['id' =>  2, 'user_id' => 2, 'movie_id' =>  2, 'score' => 5, 'created_at' => $now->copy()->subDays(28)],
            ['id' =>  3, 'user_id' => 2, 'movie_id' =>  3, 'score' => 5, 'created_at' => $now->copy()->subDays(25)],
            ['id' =>  4, 'user_id' => 2, 'movie_id' => 10, 'score' => 4, 'created_at' => $now->copy()->subDays(20)],

            // nisa (3)
            ['id' =>  5, 'user_id' => 3, 'movie_id' =>  4, 'score' => 5, 'created_at' => $now->copy()->subDays(29)],
            ['id' =>  6, 'user_id' => 3, 'movie_id' =>  8, 'score' => 5, 'created_at' => $now->copy()->subDays(27)],
            ['id' =>  7, 'user_id' => 3, 'movie_id' =>  9, 'score' => 5, 'created_at' => $now->copy()->subDays(22)],
            ['id' =>  8, 'user_id' => 3, 'movie_id' =>  2, 'score' => 4, 'created_at' => $now->copy()->subDays(15)],

            // budi (4)
            ['id' =>  9, 'user_id' => 4, 'movie_id' =>  6, 'score' => 5, 'created_at' => $now->copy()->subDays(28)],
            ['id' => 10, 'user_id' => 4, 'movie_id' =>  7, 'score' => 4, 'created_at' => $now->copy()->subDays(24)],
            ['id' => 11, 'user_id' => 4, 'movie_id' => 11, 'score' => 5, 'created_at' => $now->copy()->subDays(18)],

            // raka (5)
            ['id' => 12, 'user_id' => 5, 'movie_id' =>  1, 'score' => 4, 'created_at' => $now->copy()->subDays(26)],
            ['id' => 13, 'user_id' => 5, 'movie_id' =>  5, 'score' => 5, 'created_at' => $now->copy()->subDays(21)],
            ['id' => 14, 'user_id' => 5, 'movie_id' => 12, 'score' => 5, 'created_at' => $now->copy()->subDays(14)],

            // siska (6)
            ['id' => 15, 'user_id' => 6, 'movie_id' =>  3, 'score' => 5, 'created_at' => $now->copy()->subDays(25)],
            ['id' => 16, 'user_id' => 6, 'movie_id' =>  4, 'score' => 5, 'created_at' => $now->copy()->subDays(19)],
            ['id' => 17, 'user_id' => 6, 'movie_id' =>  9, 'score' => 4, 'created_at' => $now->copy()->subDays(12)],

            // dimas (7)
            ['id' => 18, 'user_id' => 7, 'movie_id' =>  5, 'score' => 4, 'created_at' => $now->copy()->subDays(23)],
            ['id' => 19, 'user_id' => 7, 'movie_id' =>  6, 'score' => 4, 'created_at' => $now->copy()->subDays(17)],
            ['id' => 20, 'user_id' => 7, 'movie_id' => 12, 'score' => 5, 'created_at' => $now->copy()->subDays(10)],
        ]);
    }
}