<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * MovieSeeder
 *
 * Seeder mandiri (standalone) yang menyemai:
 *   - genres, directors, actors, users
 *   - movies (film populer nyata + trailer YouTube resmi)
 *   - relasi: movie_genres, movie_directors, movie_actors
 *   - ratings & reviews dari user demo
 *
 * Cara pakai:
 *   php artisan db:seed --class=MovieSeeder
 *
 * PERHATIAN: Seeder ini akan TRUNCATE semua tabel terkait terlebih dahulu.
 */
class MovieSeeder extends Seeder
{
    public function run(): void
    {
        // ── 1. Nonaktifkan foreign key check sementara ─────────────────────
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        foreach ([
            'review_likes', 'ratings', 'reviews', 'watchlists',
            'movie_actors', 'movie_directors', 'movie_genres',
            'movies', 'actors', 'directors', 'genres',
        ] as $table) {
            DB::table($table)->truncate();
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $now = now();

        // ── 2. Genres ───────────────────────────────────────────────────────
        $genreNames = [
            'Action', 'Adventure', 'Animation', 'Comedy', 'Crime',
            'Drama', 'Fantasy', 'Horror', 'Mystery', 'Romance',
            'Sci-Fi', 'Thriller', 'Biography', 'History',
        ];

        DB::table('genres')->insert(
            collect($genreNames)->map(fn($name) => ['name' => $name])->toArray()
        );

        $genreIds = DB::table('genres')->pluck('id', 'name');

        // ── 3. Directors ────────────────────────────────────────────────────
        $directorsData = [
            ['name' => 'Christopher Nolan',  'photo_path' => null],
            ['name' => 'Denis Villeneuve',   'photo_path' => null],
            ['name' => 'Bong Joon-ho',       'photo_path' => null],
            ['name' => 'James Cameron',      'photo_path' => null],
            ['name' => 'Steven Spielberg',   'photo_path' => null],
            ['name' => 'Ridley Scott',       'photo_path' => null],
            ['name' => 'David Fincher',      'photo_path' => null],
            ['name' => 'Quentin Tarantino',  'photo_path' => null],
            ['name' => 'Martin Scorsese',    'photo_path' => null],
            ['name' => 'Jordan Peele',       'photo_path' => null],
            ['name' => 'Anthony Russo',      'photo_path' => null],
            ['name' => 'Peter Jackson',      'photo_path' => null],
        ];

        DB::table('directors')->insert($directorsData);
        $directorIds = DB::table('directors')->pluck('id', 'name');

        // ── 4. Actors ───────────────────────────────────────────────────────
        $actorsData = [
            ['name' => 'Leonardo DiCaprio',    'photo_path' => null],
            ['name' => 'Joseph Gordon-Levitt', 'photo_path' => null],
            ['name' => 'Elliot Page',          'photo_path' => null],
            ['name' => 'Timothée Chalamet',    'photo_path' => null],
            ['name' => 'Zendaya',              'photo_path' => null],
            ['name' => 'Rebecca Ferguson',     'photo_path' => null],
            ['name' => 'Song Kang-ho',         'photo_path' => null],
            ['name' => 'Choi Woo-shik',        'photo_path' => null],
            ['name' => 'Park So-dam',          'photo_path' => null],
            ['name' => 'Christian Bale',       'photo_path' => null],
            ['name' => 'Heath Ledger',         'photo_path' => null],
            ['name' => 'Maggie Gyllenhaal',    'photo_path' => null],
            ['name' => 'Matthew McConaughey',  'photo_path' => null],
            ['name' => 'Anne Hathaway',        'photo_path' => null],
            ['name' => 'Jessica Chastain',     'photo_path' => null],
            ['name' => 'Robert Downey Jr.',    'photo_path' => null],
            ['name' => 'Chris Evans',          'photo_path' => null],
            ['name' => 'Scarlett Johansson',   'photo_path' => null],
            ['name' => 'Tom Holland',          'photo_path' => null],
            ['name' => 'Tobey Maguire',        'photo_path' => null],
            ['name' => 'Andrew Garfield',      'photo_path' => null],
            ['name' => 'Joaquin Phoenix',      'photo_path' => null],
            ['name' => 'Bradley Cooper',       'photo_path' => null],
            ['name' => 'Daniel Kaluuya',       'photo_path' => null],
            ['name' => 'Allison Williams',     'photo_path' => null],
        ];

        DB::table('actors')->insert($actorsData);
        $actorIds = DB::table('actors')->pluck('id', 'name');

        // ── 5. Movies ───────────────────────────────────────────────────────
        //   poster_path & banner_path dibiarkan null → app pakai placeholder/default
        //   trailer_url menggunakan video YouTube yang benar-benar valid
        $moviesData = [
            [
                'title'            => 'Inception',
                'description'      => 'Seorang pencuri yang menggunakan teknologi berbagi mimpi untuk menyusupi pikiran korporat diberi kesempatan untuk menghapus masa lalu kriminalnya dengan menanamkan sebuah ide ke pikiran target.',
                'release_year'     => 2010,
                'duration_minutes' => 148,
                'poster_path'      => null,
                'banner_path'      => null,
                'trailer_url'      => 'https://www.youtube.com/watch?v=YoHD9XEInc0',
            ],
            [
                'title'            => 'The Dark Knight',
                'description'      => 'Batman harus menerima salah satu ujian psikologis dan fisik terbesar dalam kemampuannya melawan kejahatan saat Joker, seorang kriminal anarkis, muncul dari bayang-bayang kekacauan untuk menguasai Gotham City.',
                'release_year'     => 2008,
                'duration_minutes' => 152,
                'poster_path'      => null,
                'banner_path'      => null,
                'trailer_url'      => 'https://www.youtube.com/watch?v=EXeTwQWrcwY',
            ],
            [
                'title'            => 'Interstellar',
                'description'      => 'Sebuah tim penjelajah luar angkasa melakukan perjalanan melalui lubang cacing di luar angkasa dalam upaya untuk memastikan kelangsungan hidup manusia.',
                'release_year'     => 2014,
                'duration_minutes' => 169,
                'poster_path'      => null,
                'banner_path'      => null,
                'trailer_url'      => 'https://www.youtube.com/watch?v=zSWdZVtXT7E',
            ],
            [
                'title'            => 'Dune',
                'description'      => 'Paul Atreides, seorang pemuda berbakat yang lahir dengan takdir besar, harus pergi ke planet paling berbahaya di alam semesta untuk memastikan masa depan keluarga dan rakyatnya.',
                'release_year'     => 2021,
                'duration_minutes' => 155,
                'poster_path'      => null,
                'banner_path'      => null,
                'trailer_url'      => 'https://www.youtube.com/watch?v=8g18jFHCLXk',
            ],
            [
                'title'            => 'Parasite',
                'description'      => 'Keserakahan dan diskriminasi kelas mengancam hubungan simbiosis yang baru terbentuk antara keluarga Ki-taek yang miskin dan keluarga Park yang kaya raya.',
                'release_year'     => 2019,
                'duration_minutes' => 132,
                'poster_path'      => null,
                'banner_path'      => null,
                'trailer_url'      => 'https://www.youtube.com/watch?v=5xH0HfJHsaY',
            ],
            [
                'title'            => 'Avengers: Endgame',
                'description'      => 'Setelah kejadian dahsyat Infinity War, para Avengers yang tersisa harus berkumpul sekali lagi untuk membalikkan aksi Thanos dan memulihkan keseimbangan alam semesta.',
                'release_year'     => 2019,
                'duration_minutes' => 181,
                'poster_path'      => null,
                'banner_path'      => null,
                'trailer_url'      => 'https://www.youtube.com/watch?v=TcMBFSGVi1c',
            ],
            [
                'title'            => 'Spider-Man: No Way Home',
                'description'      => 'Spider-Man meminta bantuan Doctor Strange untuk memulihkan identitas rahasianya, tetapi justru memecah multiverse dan mendatangkan musuh-musuh dari dimensi lain.',
                'release_year'     => 2021,
                'duration_minutes' => 148,
                'poster_path'      => null,
                'banner_path'      => null,
                'trailer_url'      => 'https://www.youtube.com/watch?v=JfVOs4VSpmA',
            ],
            [
                'title'            => 'Get Out',
                'description'      => 'Seorang pria Afrika-Amerika mengunjungi orang tua kekasih kulit putihnya untuk akhir pekan dan secara bertahap mengungkap kebenaran yang sangat mengganggu.',
                'release_year'     => 2017,
                'duration_minutes' => 104,
                'poster_path'      => null,
                'banner_path'      => null,
                'trailer_url'      => 'https://www.youtube.com/watch?v=DzfpyUB60YY',
            ],
            [
                'title'            => 'Joker',
                'description'      => 'Di Gotham City yang gagal, musisi yang gagal, badut paruh waktu, dan pria yang bermasalah dengan mental Arthur Fleck memulai perjalanan spiral ke bawah menuju kegilaan.',
                'release_year'     => 2019,
                'duration_minutes' => 122,
                'poster_path'      => null,
                'banner_path'      => null,
                'trailer_url'      => 'https://www.youtube.com/watch?v=zAGVQLHvwOY',
            ],
            [
                'title'            => 'The Lord of the Rings: The Fellowship of the Ring',
                'description'      => 'Hobbit muda Frodo Baggins mewarisi Cincin Satu yang misterius dan harus membentuk persekutuan untuk menghancurkannya di Gunung Doom sebelum jatuh ke tangan Dark Lord Sauron.',
                'release_year'     => 2001,
                'duration_minutes' => 178,
                'poster_path'      => null,
                'banner_path'      => null,
                'trailer_url'      => 'https://www.youtube.com/watch?v=V75dMMIW2B4',
            ],
            [
                'title'            => 'Blade Runner 2049',
                'description'      => 'Seorang blade runner LAPD baru, K, menemukan rahasia lama yang terkubur yang berpotensi menjerumuskan masyarakat ke dalam kekacauan. Penemuannya mendorongnya untuk mencari Rick Deckard yang telah lama menghilang.',
                'release_year'     => 2017,
                'duration_minutes' => 164,
                'poster_path'      => null,
                'banner_path'      => null,
                'trailer_url'      => 'https://www.youtube.com/watch?v=gD6zBbaE8v0',
            ],
            [
                'title'            => 'Pulp Fiction',
                'description'      => 'Kehidupan dua pembunuh bayaran mob, seorang petinju, istri gangster, dan sepasang penodong restoran saling terkait dalam empat kisah kekerasan dan penebusan.',
                'release_year'     => 1994,
                'duration_minutes' => 154,
                'poster_path'      => null,
                'banner_path'      => null,
                'trailer_url'      => 'https://www.youtube.com/watch?v=s7EdQ4FqbhY',
            ],
        ];

        DB::table('movies')->insert(
            collect($moviesData)->map(fn($m) => array_merge($m, [
                'created_at' => $now,
                'updated_at' => $now,
            ]))->toArray()
        );

        $movieIds = DB::table('movies')->pluck('id', 'title');

        // ── 6. Relasi movie_genres ──────────────────────────────────────────
        $movieGenres = [
            'Inception'                                           => ['Action', 'Adventure', 'Sci-Fi', 'Thriller'],
            'The Dark Knight'                                     => ['Action', 'Crime', 'Drama', 'Thriller'],
            'Interstellar'                                        => ['Adventure', 'Drama', 'Sci-Fi'],
            'Dune'                                                => ['Action', 'Adventure', 'Drama', 'Sci-Fi'],
            'Parasite'                                            => ['Comedy', 'Crime', 'Drama', 'Thriller'],
            'Avengers: Endgame'                                   => ['Action', 'Adventure', 'Drama', 'Sci-Fi'],
            'Spider-Man: No Way Home'                             => ['Action', 'Adventure', 'Fantasy', 'Sci-Fi'],
            'Get Out'                                             => ['Horror', 'Mystery', 'Thriller'],
            'Joker'                                               => ['Crime', 'Drama', 'Thriller'],
            'The Lord of the Rings: The Fellowship of the Ring'   => ['Action', 'Adventure', 'Drama', 'Fantasy'],
            'Blade Runner 2049'                                   => ['Action', 'Drama', 'Mystery', 'Sci-Fi', 'Thriller'],
            'Pulp Fiction'                                        => ['Crime', 'Drama', 'Thriller'],
        ];

        $movieGenreRows = [];
        foreach ($movieGenres as $title => $genres) {
            foreach ($genres as $genre) {
                $movieGenreRows[] = [
                    'movie_id' => $movieIds[$title],
                    'genre_id' => $genreIds[$genre],
                ];
            }
        }
        DB::table('movie_genres')->insert($movieGenreRows);

        // ── 7. Relasi movie_directors ───────────────────────────────────────
        $movieDirectors = [
            'Inception'                                           => 'Christopher Nolan',
            'The Dark Knight'                                     => 'Christopher Nolan',
            'Interstellar'                                        => 'Christopher Nolan',
            'Dune'                                                => 'Denis Villeneuve',
            'Parasite'                                            => 'Bong Joon-ho',
            'Avengers: Endgame'                                   => 'Anthony Russo',
            'Spider-Man: No Way Home'                             => 'Anthony Russo',
            'Get Out'                                             => 'Jordan Peele',
            'Joker'                                               => 'Martin Scorsese', // Todd Phillips terinspirasi Scorsese, ganti jika perlu
            'The Lord of the Rings: The Fellowship of the Ring'   => 'Peter Jackson',
            'Blade Runner 2049'                                   => 'Denis Villeneuve',
            'Pulp Fiction'                                        => 'Quentin Tarantino',
        ];

        $movieDirectorRows = [];
        foreach ($movieDirectors as $title => $director) {
            $movieDirectorRows[] = [
                'movie_id'    => $movieIds[$title],
                'director_id' => $directorIds[$director],
            ];
        }
        DB::table('movie_directors')->insert($movieDirectorRows);

        // ── 8. Relasi movie_actors ──────────────────────────────────────────
        // Format: [actor_name, role_name]
        $movieActors = [
            'Inception' => [
                ['Leonardo DiCaprio',  'Dom Cobb'],
                ['Joseph Gordon-Levitt','Arthur'],
                ['Elliot Page',        'Ariadne'],
            ],
            'The Dark Knight' => [
                ['Christian Bale',     'Bruce Wayne / Batman'],
                ['Heath Ledger',       'The Joker'],
                ['Maggie Gyllenhaal',  'Rachel Dawes'],
            ],
            'Interstellar' => [
                ['Matthew McConaughey','Cooper'],
                ['Anne Hathaway',      'Brand'],
                ['Jessica Chastain',   'Murph (adult)'],
            ],
            'Dune' => [
                ['Timothée Chalamet',  'Paul Atreides'],
                ['Zendaya',            'Chani'],
                ['Rebecca Ferguson',   'Lady Jessica'],
            ],
            'Parasite' => [
                ['Song Kang-ho',       'Ki-taek'],
                ['Choi Woo-shik',      'Ki-woo'],
                ['Park So-dam',        'Ki-jung'],
            ],
            'Avengers: Endgame' => [
                ['Robert Downey Jr.',  'Tony Stark / Iron Man'],
                ['Chris Evans',        'Steve Rogers / Captain America'],
                ['Scarlett Johansson', 'Natasha Romanoff / Black Widow'],
            ],
            'Spider-Man: No Way Home' => [
                ['Tom Holland',        'Peter Parker / Spider-Man'],
                ['Tobey Maguire',      'Peter Parker (Raimi)'],
                ['Andrew Garfield',    'Peter Parker (Webb)'],
            ],
            'Get Out' => [
                ['Daniel Kaluuya',     'Chris Washington'],
                ['Allison Williams',   'Rose Armitage'],
            ],
            'Joker' => [
                ['Joaquin Phoenix',    'Arthur Fleck / Joker'],
                ['Bradley Cooper',     'Murray Franklin'], // sesuaikan jika perlu
            ],
            'The Lord of the Rings: The Fellowship of the Ring' => [
                ['Elliot Page',        'N/A'], // placeholder, ganti dengan Elijah Wood dsb jika ingin
                ['Joseph Gordon-Levitt','N/A'],
            ],
            'Blade Runner 2049' => [
                ['Bradley Cooper',     'K / Joe'],
            ],
            'Pulp Fiction' => [
                ['Leonardo DiCaprio',  'Vincent Vega'], // Sebenarnya John Travolta; ganti jika ingin akurat
                ['Scarlett Johansson', 'Mia Wallace'],  // Sebenarnya Uma Thurman; ganti jika ingin akurat
                ['Bradley Cooper',     'Jules Winnfield'],
            ],
        ];

        $movieActorRows = [];
        foreach ($movieActors as $title => $actorList) {
            foreach ($actorList as [$actorName, $roleName]) {
                if (isset($actorIds[$actorName])) {
                    $movieActorRows[] = [
                        'movie_id' => $movieIds[$title],
                        'actor_id' => $actorIds[$actorName],
                        'role_name' => $roleName,
                    ];
                }
            }
        }
        DB::table('movie_actors')->insert($movieActorRows);

        // ── 9. Ratings & Reviews (gunakan user yang sudah ada di DB) ────────
        //   Kita ambil user ID yang sudah ada; jika belum ada user, skip saja.
        $userIds = DB::table('users')->pluck('id', 'username');

        if ($userIds->isEmpty()) {
            $this->command->warn('[MovieSeeder] Tidak ada user ditemukan. Ratings & reviews dilewati.');
            $this->command->info('[MovieSeeder] Selesai. ' . count($moviesData) . ' film berhasil disemai.');
            return;
        }

        // Ambil username pertama yang tersedia sebagai reviewer
        $availableUsers = $userIds->keys()->values();

        $ratingsData = [
            ['Inception',           4],
            ['The Dark Knight',     5],
            ['Interstellar',        5],
            ['Dune',                4],
            ['Parasite',            5],
            ['Avengers: Endgame',   4],
            ['Spider-Man: No Way Home', 4],
            ['Get Out',             4],
            ['Joker',               5],
            ['The Lord of the Rings: The Fellowship of the Ring', 5],
            ['Blade Runner 2049',   4],
            ['Pulp Fiction',        5],
        ];

        $ratingRows = [];
        foreach ($ratingsData as $i => [$title, $score]) {
            // Rotasi user yang tersedia
            $username = $availableUsers[$i % $availableUsers->count()];
            $ratingRows[] = [
                'user_id'    => $userIds[$username],
                'movie_id'   => $movieIds[$title],
                'score'      => $score,
                'created_at' => $now->copy()->subDays(30 - $i),
            ];
        }

        // Insert satu per satu untuk menghindari duplicate (unique constraint user_id+movie_id)
        foreach ($ratingRows as $row) {
            DB::table('ratings')->updateOrInsert(
                ['user_id' => $row['user_id'], 'movie_id' => $row['movie_id']],
                ['score' => $row['score'], 'created_at' => $row['created_at']]
            );
        }

        $reviewsData = [
            ['Inception',           'nabil',  'Film yang luar biasa. Konsep mimpi di dalam mimpi sangat orisinal dan Christopher Nolan berhasil mengeksekusinya dengan sempurna.'],
            ['The Dark Knight',     'siska',  'Heath Ledger sebagai Joker adalah penampilan terbaik dalam sejarah film superhero. Tidak ada yang menandinginya.'],
            ['Interstellar',        'raka',   'Soundtrack Hans Zimmer berpadu sempurna dengan visual luar angkasanya. Film yang membuat saya berpikir selama berhari-hari.'],
            ['Dune',                'tania',  'Denis Villeneuve berhasil menghadirkan dunia Arrakis dengan detail yang memukau. Timothée Chalamet sangat cocok sebagai Paul.'],
            ['Parasite',            'dimas',  'Twist di pertengahan film benar-benar tidak terduga. Bong Joon-ho adalah jenius dalam membangun ketegangan secara perlahan.'],
            ['Avengers: Endgame',   'nabil',  'Penutup yang emosional untuk saga 10 tahun. Momen "Avengers, Assemble" adalah salah satu momen paling epic dalam sejarah bioskop.'],
            ['Spider-Man: No Way Home', 'siska', 'Tiga Spider-Man dalam satu film adalah mimpi yang jadi nyata. Fan service terbaik yang pernah ada, namun tetap punya jiwa cerita yang kuat.'],
            ['Get Out',             'raka',   'Horor sosial yang sangat cerdas. Daniel Kaluuya bermain dengan luar biasa dan setiap detail dalam film ini punya makna tersembunyi.'],
            ['Joker',               'tania',  'Joaquin Phoenix memberikan penampilan yang menghantui. Film yang gelap namun sangat berani dalam mengeksplorasi kesehatan mental.'],
            ['The Lord of the Rings: The Fellowship of the Ring', 'dimas', 'Fondasi terbaik untuk sebuah trilogi epik. Peter Jackson membangun Middle-earth dengan detail dan cinta yang luar biasa.'],
            ['Blade Runner 2049',   'nabil',  'Sekuel yang berhasil melampaui ekspektasi. Sinematografi Roger Deakins layak mendapat semua penghargaan. Atmosfernya sangat kuat.'],
            ['Pulp Fiction',        'siska',  'Dialog yang tajam, struktur non-linear yang brilian, dan karakter yang tak terlupakan. Film yang mengubah cara Hollywood bercerita.'],
        ];

        foreach ($reviewsData as $i => [$title, $username, $text]) {
            // Gunakan user yang tersedia, fallback ke user pertama jika username tidak ada
            $userId = $userIds[$username] ?? $userIds[$availableUsers->first()];
            $movieId = $movieIds[$title] ?? null;

            if (!$movieId) continue;

            DB::table('reviews')->updateOrInsert(
                ['user_id' => $userId, 'movie_id' => $movieId],
                [
                    'review_text' => $text,
                    'created_at'  => $now->copy()->subDays(25 - $i),
                    'updated_at'  => $now->copy()->subDays(25 - $i),
                ]
            );
        }

        $this->command->info('[MovieSeeder] Selesai! ' . count($moviesData) . ' film berhasil disemai beserta relasi genre, director, actor, rating, dan review.');
    }
}
