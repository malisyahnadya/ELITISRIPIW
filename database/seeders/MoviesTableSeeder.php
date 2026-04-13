<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MoviesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        DB::table('movies')->delete();
        
        DB::table('movies')->insert(array (
            0 => 
            array (
                'id' => 1,
                'title' => 'Inception',
                'description' => 'A thief who steals corporate secrets through dream-sharing technology is given a chance to erase his criminal past.',
                'release_year' => 2010,
                'duration_minutes' => 148,
                'poster_path' => 'uploads/posters/inception.jpg',
                'banner_path' => NULL,
                'created_at' => '2026-04-05 11:23:44',
                'updated_at' => '2026-04-05 11:23:44',
            ),
            1 => 
            array (
                'id' => 2,
                'title' => 'The Matrix',
                'description' => 'A hacker learns about the true nature of reality and his role in the war against its controllers.',
                'release_year' => 1999,
                'duration_minutes' => 136,
                'poster_path' => 'uploads/posters/matrix.jpg',
                'banner_path' => NULL,
                'created_at' => '2026-04-05 11:23:44',
                'updated_at' => '2026-04-05 11:23:44',
            ),
            2 => 
            array (
                'id' => 3,
                'title' => 'The Dark Knight',
                'description' => 'Batman faces the Joker, a criminal mastermind who wants to plunge Gotham into anarchy.',
                'release_year' => 2008,
                'duration_minutes' => 152,
                'poster_path' => 'uploads/posters/dark_knight.jpg',
                'banner_path' => NULL,
                'created_at' => '2026-04-05 11:23:44',
                'updated_at' => '2026-04-05 11:23:44',
            ),
            3 => 
            array (
                'id' => 4,
                'title' => 'Interstellar',
                'description' => 'Explorers travel through a wormhole in space in an attempt to ensure humanity’s survival.',
                'release_year' => 2014,
                'duration_minutes' => 169,
                'poster_path' => 'uploads/posters/interstellar.jpg',
                'banner_path' => NULL,
                'created_at' => '2026-04-05 11:23:44',
                'updated_at' => '2026-04-05 11:23:44',
            ),
            4 => 
            array (
                'id' => 5,
                'title' => 'Blade Runner 2049',
                'description' => 'A young blade runner discovers a secret that could plunge society into chaos.',
                'release_year' => 2017,
                'duration_minutes' => 164,
                'poster_path' => 'uploads/posters/br2049.jpg',
                'banner_path' => NULL,
                'created_at' => '2026-04-05 11:23:44',
                'updated_at' => '2026-04-05 11:23:44',
            ),
            5 => 
            array (
                'id' => 6,
                'title' => 'Dune',
                'description' => 'A noble family becomes embroiled in a war for control over the galaxy’s most valuable asset.',
                'release_year' => 2021,
                'duration_minutes' => 155,
                'poster_path' => 'uploads/posters/dune.jpg',
                'banner_path' => NULL,
                'created_at' => '2026-04-05 11:23:44',
                'updated_at' => '2026-04-05 11:23:44',
            ),
            6 => 
            array (
                'id' => 7,
                'title' => 'Spider-Man: No Way Home',
                'description' => 'Spider-Man seeks help to restore his secret identity, but breaks the multiverse.',
                'release_year' => 2021,
                'duration_minutes' => 148,
                'poster_path' => 'uploads/posters/nwh.jpg',
                'banner_path' => NULL,
                'created_at' => '2026-04-05 11:23:44',
                'updated_at' => '2026-04-05 11:23:44',
            ),
            7 => 
            array (
                'id' => 8,
                'title' => 'Avengers: Endgame',
                'description' => 'The Avengers assemble once more to reverse Thanos’ actions and restore balance.',
                'release_year' => 2019,
                'duration_minutes' => 181,
                'poster_path' => 'uploads/posters/endgame.jpg',
                'banner_path' => NULL,
                'created_at' => '2026-04-05 11:23:44',
                'updated_at' => '2026-04-05 11:23:44',
            ),
            8 => 
            array (
                'id' => 9,
                'title' => 'Parasite',
                'description' => 'Greed and class discrimination threaten the newly formed symbiotic relationship between two families.',
                'release_year' => 2019,
                'duration_minutes' => 132,
                'poster_path' => 'uploads/posters/parasite.jpg',
                'banner_path' => NULL,
                'created_at' => '2026-04-05 11:23:44',
                'updated_at' => '2026-04-05 11:23:44',
            ),
            9 => 
            array (
                'id' => 10,
                'title' => 'Get Out',
                'description' => 'A young African-American visits his white girlfriend’s parents for the weekend, where secrets emerge.',
                'release_year' => 2017,
                'duration_minutes' => 104,
                'poster_path' => 'uploads/posters/get_out.jpg',
                'banner_path' => NULL,
                'created_at' => '2026-04-05 11:23:44',
                'updated_at' => '2026-04-05 11:23:44',
            ),
        ));
        
        
    }
}