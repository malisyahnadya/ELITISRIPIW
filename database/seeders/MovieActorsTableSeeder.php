<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Referensi aktor → lihat ActorsTableSeeder
 *
 *  1 = Leonardo DiCaprio     |  2 = Joseph Gordon-Levitt |  3 = Elliot Page
 *  4 = Tom Hardy             |  5 = Christian Bale       |  6 = Heath Ledger
 *  7 = Aaron Eckhart         |  8 = Maggie Gyllenhaal    |  9 = Matthew McConaughey
 * 10 = Anne Hathaway         | 11 = Jessica Chastain     | 12 = Song Kang-ho
 * 13 = Choi Woo-shik         | 14 = Park So-dam          | 15 = Lee Sun-kyun
 * 16 = Timothée Chalamet     | 17 = Zendaya              | 18 = Rebecca Ferguson
 * 19 = Oscar Isaac           | 20 = Robert Downey Jr.    | 21 = Chris Evans
 * 22 = Scarlett Johansson    | 23 = Mark Ruffalo         | 24 = Tom Holland
 * 25 = Tobey Maguire         | 26 = Andrew Garfield      | 27 = Benedict Cumberbatch
 * 28 = Daniel Kaluuya        | 29 = Allison Williams     | 30 = Joaquin Phoenix
 * 31 = Robert De Niro        | 32 = Zazie Beetz          | 33 = Ryan Gosling
 * 34 = Harrison Ford         | 35 = Ana de Armas         | 36 = John Travolta
 * 37 = Samuel L. Jackson     | 38 = Uma Thurman          | 39 = Elijah Wood
 * 40 = Ian McKellen          | 41 = Viggo Mortensen
 */
class MovieActorsTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('movie_actors')->delete();

        DB::table('movie_actors')->insert([
            // ── Inception (movie_id: 1) ──────────────────────────────────────
            ['movie_id' =>  1, 'actor_id' =>  1, 'role_name' => 'Dom Cobb'],
            ['movie_id' =>  1, 'actor_id' =>  2, 'role_name' => 'Arthur'],
            ['movie_id' =>  1, 'actor_id' =>  3, 'role_name' => 'Ariadne'],
            ['movie_id' =>  1, 'actor_id' =>  4, 'role_name' => 'Eames'],

            // ── The Dark Knight (movie_id: 2) ────────────────────────────────
            ['movie_id' =>  2, 'actor_id' =>  5, 'role_name' => 'Bruce Wayne / Batman'],
            ['movie_id' =>  2, 'actor_id' =>  6, 'role_name' => 'The Joker'],
            ['movie_id' =>  2, 'actor_id' =>  7, 'role_name' => 'Harvey Dent / Two-Face'],
            ['movie_id' =>  2, 'actor_id' =>  8, 'role_name' => 'Rachel Dawes'],

            // ── Interstellar (movie_id: 3) ───────────────────────────────────
            ['movie_id' =>  3, 'actor_id' =>  9, 'role_name' => 'Cooper'],
            ['movie_id' =>  3, 'actor_id' => 10, 'role_name' => 'Dr. Amelia Brand'],
            ['movie_id' =>  3, 'actor_id' => 11, 'role_name' => 'Murph (adult)'],

            // ── Parasite (movie_id: 4) ───────────────────────────────────────
            ['movie_id' =>  4, 'actor_id' => 12, 'role_name' => 'Ki-taek'],
            ['movie_id' =>  4, 'actor_id' => 13, 'role_name' => 'Ki-woo'],
            ['movie_id' =>  4, 'actor_id' => 14, 'role_name' => 'Ki-jung'],
            ['movie_id' =>  4, 'actor_id' => 15, 'role_name' => 'Park Dong-ik'],

            // ── Dune (movie_id: 5) ───────────────────────────────────────────
            ['movie_id' =>  5, 'actor_id' => 16, 'role_name' => 'Paul Atreides'],
            ['movie_id' =>  5, 'actor_id' => 17, 'role_name' => 'Chani'],
            ['movie_id' =>  5, 'actor_id' => 18, 'role_name' => 'Lady Jessica'],
            ['movie_id' =>  5, 'actor_id' => 19, 'role_name' => 'Duke Leto Atreides'],

            // ── Avengers: Endgame (movie_id: 6) ─────────────────────────────
            ['movie_id' =>  6, 'actor_id' => 20, 'role_name' => 'Tony Stark / Iron Man'],
            ['movie_id' =>  6, 'actor_id' => 21, 'role_name' => 'Steve Rogers / Captain America'],
            ['movie_id' =>  6, 'actor_id' => 22, 'role_name' => 'Natasha Romanoff / Black Widow'],
            ['movie_id' =>  6, 'actor_id' => 23, 'role_name' => 'Bruce Banner / Hulk'],

            // ── Spider-Man: No Way Home (movie_id: 7) ────────────────────────
            ['movie_id' =>  7, 'actor_id' => 24, 'role_name' => 'Peter Parker (MCU)'],
            ['movie_id' =>  7, 'actor_id' => 25, 'role_name' => 'Peter Parker (Raimi)'],
            ['movie_id' =>  7, 'actor_id' => 26, 'role_name' => 'Peter Parker (Webb)'],
            ['movie_id' =>  7, 'actor_id' => 27, 'role_name' => 'Doctor Strange'],

            // ── Get Out (movie_id: 8) ────────────────────────────────────────
            ['movie_id' =>  8, 'actor_id' => 28, 'role_name' => 'Chris Washington'],
            ['movie_id' =>  8, 'actor_id' => 29, 'role_name' => 'Rose Armitage'],

            // ── Joker (movie_id: 9) ──────────────────────────────────────────
            ['movie_id' =>  9, 'actor_id' => 30, 'role_name' => 'Arthur Fleck / Joker'],
            ['movie_id' =>  9, 'actor_id' => 31, 'role_name' => 'Murray Franklin'],
            ['movie_id' =>  9, 'actor_id' => 32, 'role_name' => 'Sophie Dumond'],

            // ── Blade Runner 2049 (movie_id: 10) ─────────────────────────────
            ['movie_id' => 10, 'actor_id' => 33, 'role_name' => 'K / Joe'],
            ['movie_id' => 10, 'actor_id' => 34, 'role_name' => 'Rick Deckard'],
            ['movie_id' => 10, 'actor_id' => 35, 'role_name' => 'Joi'],

            // ── Pulp Fiction (movie_id: 11) ───────────────────────────────────
            ['movie_id' => 11, 'actor_id' => 36, 'role_name' => 'Vincent Vega'],
            ['movie_id' => 11, 'actor_id' => 37, 'role_name' => 'Jules Winnfield'],
            ['movie_id' => 11, 'actor_id' => 38, 'role_name' => 'Mia Wallace'],

            // ── The Lord of the Rings: The Fellowship of the Ring (movie_id: 12) ──
            ['movie_id' => 12, 'actor_id' => 39, 'role_name' => 'Frodo Baggins'],
            ['movie_id' => 12, 'actor_id' => 40, 'role_name' => 'Gandalf the Grey'],
            ['movie_id' => 12, 'actor_id' => 41, 'role_name' => 'Aragorn'],

            // ── 500 Days of Summer (movie_id: 13) ────────────────────────────
            // Joseph Gordon-Levitt = actor_id 2 (sudah ada dari Inception)
            ['movie_id' => 13, 'actor_id' =>  2, 'role_name' => 'Tom Hansen'],
            ['movie_id' => 13, 'actor_id' => 42, 'role_name' => 'Summer Finn'],

            // ── Venom: Let There Be Carnage (movie_id: 14) ───────────────────
            // Tom Hardy = actor_id 4 (sudah ada dari Inception)
            ['movie_id' => 14, 'actor_id' =>  4, 'role_name' => 'Eddie Brock / Venom'],
            ['movie_id' => 14, 'actor_id' => 43, 'role_name' => 'Cletus Kasady / Carnage'],
            ['movie_id' => 14, 'actor_id' => 44, 'role_name' => 'Anne Weying'],

            // ── Iron Man 3 (movie_id: 15) ─────────────────────────────────────
            // Robert Downey Jr. = actor_id 20 (sudah ada dari Avengers: Endgame)
            ['movie_id' => 15, 'actor_id' => 20, 'role_name' => 'Tony Stark / Iron Man'],
            ['movie_id' => 15, 'actor_id' => 45, 'role_name' => 'Pepper Potts'],
            ['movie_id' => 15, 'actor_id' => 46, 'role_name' => 'James Rhodes / War Machine'],
            ['movie_id' => 15, 'actor_id' => 47, 'role_name' => 'Aldrich Killian'],

            // ── Kimi no Na wa. (movie_id: 16) ─────────────────────────────────
            ['movie_id' => 16, 'actor_id' => 48, 'role_name' => 'Taki Tachibana (voice)'],
            ['movie_id' => 16, 'actor_id' => 49, 'role_name' => 'Mitsuha Miyamizu (voice)'],
        ]);
    }
}