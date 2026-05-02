<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class ReviewsTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('reviews')->delete();

        $now = Carbon::now();

        DB::table('reviews')->insert([
            // amir (user_id: 2)
            [
                'id'          =>  1,
                'user_id'     =>  2,
                'movie_id'    =>  1,
                'review_text' => 'Konsep dream-within-a-dream yang orisinal dan dieksekusi rapi. Ending yang ambigu justru jadi kekuatannya, bukan kelemahan.',
                'created_at'  => $now->copy()->subDays(29),
                'updated_at'  => $now->copy()->subDays(29),
            ],
            [
                'id'          =>  2,
                'user_id'     =>  2,
                'movie_id'    =>  2,
                'review_text' => 'Heath Ledger sebagai Joker adalah penampilan yang belum tertandingi. Film superhero yang terasa bukan seperti film superhero.',
                'created_at'  => $now->copy()->subDays(27),
                'updated_at'  => $now->copy()->subDays(27),
            ],
            [
                'id'          =>  3,
                'user_id'     =>  2,
                'movie_id'    =>  3,
                'review_text' => 'Ambisi naratifnya luar biasa. Soundtrack Hans Zimmer dan visual gravitasi nol berpadu sempurna. Film yang membuat saya berpikir berhari-hari.',
                'created_at'  => $now->copy()->subDays(24),
                'updated_at'  => $now->copy()->subDays(24),
            ],

            // nisa (user_id: 3)
            [
                'id'          =>  4,
                'user_id'     =>  3,
                'movie_id'    =>  4,
                'review_text' => 'Twist pertengahan film benar-benar tidak terduga. Bong Joon-ho membangun ketegangan dengan sangat sabar dan hasilnya luar biasa.',
                'created_at'  => $now->copy()->subDays(28),
                'updated_at'  => $now->copy()->subDays(28),
            ],
            [
                'id'          =>  5,
                'user_id'     =>  3,
                'movie_id'    =>  8,
                'review_text' => 'Horor sosial yang cerdas. Setiap detail punya makna tersembunyi. Daniel Kaluuya tampil luar biasa dan ekspresinya berbicara lebih dari dialog.',
                'created_at'  => $now->copy()->subDays(21),
                'updated_at'  => $now->copy()->subDays(21),
            ],
            [
                'id'          =>  6,
                'user_id'     =>  3,
                'movie_id'    =>  9,
                'review_text' => 'Joaquin Phoenix memberikan penampilan yang menghantui. Film yang berani mengeksplorasi kesehatan mental tanpa filter.',
                'created_at'  => $now->copy()->subDays(14),
                'updated_at'  => $now->copy()->subDays(14),
            ],

            // budi (user_id: 4)
            [
                'id'          =>  7,
                'user_id'     =>  4,
                'movie_id'    =>  6,
                'review_text' => 'Penutup yang emosional untuk saga 10 tahun. Momen "Avengers, Assemble" adalah salah satu momen paling epik dalam sejarah bioskop.',
                'created_at'  => $now->copy()->subDays(27),
                'updated_at'  => $now->copy()->subDays(27),
            ],
            [
                'id'          =>  8,
                'user_id'     =>  4,
                'movie_id'    =>  7,
                'review_text' => 'Tiga Spider-Man dalam satu layar adalah mimpi yang jadi kenyataan. Fan service terbaik yang tetap punya hati cerita yang kuat.',
                'created_at'  => $now->copy()->subDays(23),
                'updated_at'  => $now->copy()->subDays(23),
            ],

            // raka (user_id: 5)
            [
                'id'          =>  9,
                'user_id'     =>  5,
                'movie_id'    =>  1,
                'review_text' => 'Tempo yang cepat tapi tidak pernah terasa terburu-buru. Setiap adegan dream heist terasa punya logika internalnya sendiri.',
                'created_at'  => $now->copy()->subDays(25),
                'updated_at'  => $now->copy()->subDays(25),
            ],
            [
                'id'          => 10,
                'user_id'     =>  5,
                'movie_id'    =>  5,
                'review_text' => 'Denis Villeneuve menghadirkan Arrakis dengan detail dan keagungan yang memukau. Timothée Chalamet sangat cocok sebagai Paul Atreides.',
                'created_at'  => $now->copy()->subDays(20),
                'updated_at'  => $now->copy()->subDays(20),
            ],

            // siska (user_id: 6)
            [
                'id'          => 11,
                'user_id'     =>  6,
                'movie_id'    =>  3,
                'review_text' => 'Film yang terasa seperti surat cinta untuk astronomi dan fisika. Emosionalnya juga tidak kaleng-kaleng, adegan dock sangat menyayat.',
                'created_at'  => $now->copy()->subDays(24),
                'updated_at'  => $now->copy()->subDays(24),
            ],
            [
                'id'          => 12,
                'user_id'     =>  6,
                'movie_id'    =>  4,
                'review_text' => 'Satire kelas sosial yang tajam dan tidak pernah terasa menggurui. Tiap karakter punya depth yang membuat kita tidak bisa mudah memilih siapa yang "benar".',
                'created_at'  => $now->copy()->subDays(18),
                'updated_at'  => $now->copy()->subDays(18),
            ],

            // dimas (user_id: 7)
            [
                'id'          => 13,
                'user_id'     =>  7,
                'movie_id'    =>  5,
                'review_text' => 'World-building Arrakis terasa nyata dan massif. Slow burn yang worth it, dan sinematografi Greig Fraser layak semua penghargaan yang ia dapat.',
                'created_at'  => $now->copy()->subDays(22),
                'updated_at'  => $now->copy()->subDays(22),
            ],
            [
                'id'          => 14,
                'user_id'     =>  7,
                'movie_id'    => 12,
                'review_text' => 'Peter Jackson membangun Middle-earth dengan cinta dan detail yang luar biasa. Fondasi trilogi epik terbaik yang pernah ada di layar lebar.',
                'created_at'  => $now->copy()->subDays(9),
                'updated_at'  => $now->copy()->subDays(9),
            ],
        ]);
    }
}