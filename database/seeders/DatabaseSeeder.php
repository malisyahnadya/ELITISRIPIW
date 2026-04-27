<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        $this->call(DemoMovieSeeder::class);
        $this->call(UsersTableSeeder::class); // User harus paling atas 
        $this->call(MoviesTableSeeder::class); // Movie juga harus sebelum rating/review 
        $this->call(ActorsTableSeeder::class);
        $this->call(DirectorsTableSeeder::class);
        $this->call(GenresTableSeeder::class);
        $this->call(MovieActorsTableSeeder::class);
        $this->call(MovieDirectorsTableSeeder::class);
        $this->call(MovieGenresTableSeeder::class);
        $this->call(RatingsTableSeeder::class); // Butuh User & Movie 
        $this->call(ReviewsTableSeeder::class); // Butuh User & Movie 
        $this->call(ReviewLikesTableSeeder::class); // Butuh Review & User 
        $this->call(WatchlistsTableSeeder::class); // Butuh User & Movie
    }
}
