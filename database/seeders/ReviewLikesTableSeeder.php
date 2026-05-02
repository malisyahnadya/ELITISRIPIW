<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

/**
 * Review IDs → lihat ReviewsTableSeeder
 * User tidak boleh like review miliknya sendiri.
 *
 * Review ownership:
 *  1,2,3 → amir (2)   |  4,5,6 → nisa (3)   |  7,8 → budi (4)
 *  9,10  → raka (5)   | 11,12  → siska (6)   | 13,14 → dimas (7)
 */
class ReviewLikesTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('review_likes')->delete();

        $now = Carbon::now();

        DB::table('review_likes')->insert([
            // Review 1 (amir - Inception) dilike oleh nisa, budi, raka
            ['id' =>  1, 'user_id' => 3, 'review_id' =>  1, 'created_at' => $now->copy()->subDays(28)],
            ['id' =>  2, 'user_id' => 4, 'review_id' =>  1, 'created_at' => $now->copy()->subDays(27)],
            ['id' =>  3, 'user_id' => 5, 'review_id' =>  1, 'created_at' => $now->copy()->subDays(26)],

            // Review 2 (amir - The Dark Knight) dilike oleh nisa, dimas
            ['id' =>  4, 'user_id' => 3, 'review_id' =>  2, 'created_at' => $now->copy()->subDays(26)],
            ['id' =>  5, 'user_id' => 7, 'review_id' =>  2, 'created_at' => $now->copy()->subDays(25)],

            // Review 4 (nisa - Parasite) dilike oleh amir, siska, dimas
            ['id' =>  6, 'user_id' => 2, 'review_id' =>  4, 'created_at' => $now->copy()->subDays(27)],
            ['id' =>  7, 'user_id' => 6, 'review_id' =>  4, 'created_at' => $now->copy()->subDays(25)],
            ['id' =>  8, 'user_id' => 7, 'review_id' =>  4, 'created_at' => $now->copy()->subDays(24)],

            // Review 5 (nisa - Get Out) dilike oleh budi, raka
            ['id' =>  9, 'user_id' => 4, 'review_id' =>  5, 'created_at' => $now->copy()->subDays(20)],
            ['id' => 10, 'user_id' => 5, 'review_id' =>  5, 'created_at' => $now->copy()->subDays(19)],

            // Review 7 (budi - Avengers: Endgame) dilike oleh amir, raka, siska
            ['id' => 11, 'user_id' => 2, 'review_id' =>  7, 'created_at' => $now->copy()->subDays(26)],
            ['id' => 12, 'user_id' => 5, 'review_id' =>  7, 'created_at' => $now->copy()->subDays(24)],
            ['id' => 13, 'user_id' => 6, 'review_id' =>  7, 'created_at' => $now->copy()->subDays(23)],

            // Review 10 (raka - Dune) dilike oleh nisa, dimas
            ['id' => 14, 'user_id' => 3, 'review_id' => 10, 'created_at' => $now->copy()->subDays(19)],
            ['id' => 15, 'user_id' => 7, 'review_id' => 10, 'created_at' => $now->copy()->subDays(18)],

            // Review 11 (siska - Interstellar) dilike oleh amir, budi
            ['id' => 16, 'user_id' => 2, 'review_id' => 11, 'created_at' => $now->copy()->subDays(23)],
            ['id' => 17, 'user_id' => 4, 'review_id' => 11, 'created_at' => $now->copy()->subDays(22)],

            // Review 14 (dimas - LotR) dilike oleh raka, siska
            ['id' => 18, 'user_id' => 5, 'review_id' => 14, 'created_at' => $now->copy()->subDays(8)],
            ['id' => 19, 'user_id' => 6, 'review_id' => 14, 'created_at' => $now->copy()->subDays(7)],
        ]);
    }
}