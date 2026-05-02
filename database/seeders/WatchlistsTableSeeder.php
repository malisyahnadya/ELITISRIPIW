<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class WatchlistsTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('watchlists')->delete();

        $now = Carbon::now();

        DB::table('watchlists')->insert([
            // amir (2)
            ['id' =>  1, 'user_id' => 2, 'movie_id' =>  1, 'status' => 'completed',     'created_at' => $now->copy()->subDays(30), 'updated_at' => $now->copy()->subDays(30)],
            ['id' =>  2, 'user_id' => 2, 'movie_id' =>  2, 'status' => 'completed',     'created_at' => $now->copy()->subDays(28), 'updated_at' => $now->copy()->subDays(28)],
            ['id' =>  3, 'user_id' => 2, 'movie_id' =>  5, 'status' => 'plan_to_watch', 'created_at' => $now->copy()->subDays(10), 'updated_at' => $now->copy()->subDays(10)],

            // nisa (3)
            ['id' =>  4, 'user_id' => 3, 'movie_id' =>  4, 'status' => 'completed',     'created_at' => $now->copy()->subDays(29), 'updated_at' => $now->copy()->subDays(29)],
            ['id' =>  5, 'user_id' => 3, 'movie_id' =>  8, 'status' => 'completed',     'created_at' => $now->copy()->subDays(22), 'updated_at' => $now->copy()->subDays(22)],
            ['id' =>  6, 'user_id' => 3, 'movie_id' =>  9, 'status' => 'watching',      'created_at' => $now->copy()->subDays(5),  'updated_at' => $now->copy()->subDays(5)],

            // budi (4)
            ['id' =>  7, 'user_id' => 4, 'movie_id' =>  6, 'status' => 'completed',     'created_at' => $now->copy()->subDays(28), 'updated_at' => $now->copy()->subDays(28)],
            ['id' =>  8, 'user_id' => 4, 'movie_id' =>  7, 'status' => 'completed',     'created_at' => $now->copy()->subDays(23), 'updated_at' => $now->copy()->subDays(23)],
            ['id' =>  9, 'user_id' => 4, 'movie_id' => 11, 'status' => 'plan_to_watch', 'created_at' => $now->copy()->subDays(7),  'updated_at' => $now->copy()->subDays(7)],

            // raka (5)
            ['id' => 10, 'user_id' => 5, 'movie_id' =>  1, 'status' => 'completed',     'created_at' => $now->copy()->subDays(26), 'updated_at' => $now->copy()->subDays(26)],
            ['id' => 11, 'user_id' => 5, 'movie_id' =>  5, 'status' => 'completed',     'created_at' => $now->copy()->subDays(21), 'updated_at' => $now->copy()->subDays(21)],
            ['id' => 12, 'user_id' => 5, 'movie_id' => 12, 'status' => 'watching',      'created_at' => $now->copy()->subDays(4),  'updated_at' => $now->copy()->subDays(4)],

            // siska (6)
            ['id' => 13, 'user_id' => 6, 'movie_id' =>  3, 'status' => 'completed',     'created_at' => $now->copy()->subDays(25), 'updated_at' => $now->copy()->subDays(25)],
            ['id' => 14, 'user_id' => 6, 'movie_id' =>  4, 'status' => 'completed',     'created_at' => $now->copy()->subDays(19), 'updated_at' => $now->copy()->subDays(19)],
            ['id' => 15, 'user_id' => 6, 'movie_id' =>  1, 'status' => 'plan_to_watch', 'created_at' => $now->copy()->subDays(3),  'updated_at' => $now->copy()->subDays(3)],

            // dimas (7)
            ['id' => 16, 'user_id' => 7, 'movie_id' =>  5, 'status' => 'completed',     'created_at' => $now->copy()->subDays(23), 'updated_at' => $now->copy()->subDays(23)],
            ['id' => 17, 'user_id' => 7, 'movie_id' => 12, 'status' => 'completed',     'created_at' => $now->copy()->subDays(10), 'updated_at' => $now->copy()->subDays(10)],
            ['id' => 18, 'user_id' => 7, 'movie_id' =>  6, 'status' => 'plan_to_watch', 'created_at' => $now->copy()->subDays(2),  'updated_at' => $now->copy()->subDays(2)],
        ]);
    }
}