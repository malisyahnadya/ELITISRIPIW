<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Nonaktifkan foreign key check agar truncate/delete tidak terhalang constraint
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        // Urutan truncate: dari tabel paling dependen ke paling dasar
        foreach ([
            'review_likes',
            'watchlists',
            'ratings',
            'reviews',
            'movie_actors',
            'movie_directors',
            'movie_genres',
            'movies',
            'actors',
            'directors',
            'genres',
            'users',
        ] as $table) {
            DB::table($table)->truncate();
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        // Urutan seeding: tabel dasar dulu, baru tabel yang bergantung
        $this->call([
            UsersTableSeeder::class,          // harus pertama
            MoviesTableSeeder::class,          // harus sebelum relasi
            ActorsTableSeeder::class,
            DirectorsTableSeeder::class,
            GenresTableSeeder::class,
            MovieActorsTableSeeder::class,     // butuh movies + actors
            MovieDirectorsTableSeeder::class,  // butuh movies + directors
            MovieGenresTableSeeder::class,     // butuh movies + genres
            RatingsTableSeeder::class,         // butuh users + movies
            ReviewsTableSeeder::class,         // butuh users + movies
            ReviewLikesTableSeeder::class,     // butuh reviews + users
            WatchlistsTableSeeder::class,      // butuh users + movies
        ]);
    }
}