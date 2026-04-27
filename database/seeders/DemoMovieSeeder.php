<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DemoMovieSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        foreach ([
            'review_likes',
            'ratings',
            'reviews',
            'watchlists',
            'movie_actors',
            'movie_directors',
            'movie_genres',
            'movies',
            'users',
            'actors',
            'directors',
            'genres',
        ] as $table) {
            DB::table($table)->truncate();
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $now = now();

        $genres = [
            ['name' => 'Action'],
            ['name' => 'Adventure'],
            ['name' => 'Drama'],
            ['name' => 'Thriller'],
            ['name' => 'Sci-Fi'],
            ['name' => 'Fantasy'],
            ['name' => 'Horror'],
            ['name' => 'Romance'],
            ['name' => 'Comedy'],
            ['name' => 'Animation'],
            ['name' => 'Crime'],
            ['name' => 'Mystery'],
        ];

        $directors = [
            ['name' => 'Alden Hart', 'photo_path' => 'directors/alden-hart.jpg'],
            ['name' => 'Mira Solano', 'photo_path' => 'directors/mira-solano.jpg'],
            ['name' => 'Darren Vale', 'photo_path' => 'directors/darren-vale.jpg'],
            ['name' => 'Nadia Rook', 'photo_path' => 'directors/nadia-rook.jpg'],
            ['name' => 'Ethan Cross', 'photo_path' => 'directors/ethan-cross.jpg'],
            ['name' => 'Lena Ward', 'photo_path' => 'directors/lena-ward.jpg'],
            ['name' => 'Rafiq Stone', 'photo_path' => 'directors/rafiq-stone.jpg'],
            ['name' => 'Juniper Blake', 'photo_path' => 'directors/juniper-blake.jpg'],
        ];

        $actors = [
            ['name' => 'Aria Noland', 'photo_path' => 'actors/aria-noland.jpg'],
            ['name' => 'Bram Kessler', 'photo_path' => 'actors/bram-kessler.jpg'],
            ['name' => 'Cleo Mercer', 'photo_path' => 'actors/cleo-mercer.jpg'],
            ['name' => 'Dante Voss', 'photo_path' => 'actors/dante-voss.jpg'],
            ['name' => 'Elena Quinn', 'photo_path' => 'actors/elena-quinn.jpg'],
            ['name' => 'Felix Arden', 'photo_path' => 'actors/felix-arden.jpg'],
            ['name' => 'Gina Ravin', 'photo_path' => 'actors/gina-ravin.jpg'],
            ['name' => 'Hugo Lark', 'photo_path' => 'actors/hugo-lark.jpg'],
            ['name' => 'Iris Bennett', 'photo_path' => 'actors/iris-bennett.jpg'],
            ['name' => 'Jasper Holt', 'photo_path' => 'actors/jasper-holt.jpg'],
            ['name' => 'Kaia Monroe', 'photo_path' => 'actors/kaia-monroe.jpg'],
            ['name' => 'Luca Ferris', 'photo_path' => 'actors/luca-ferris.jpg'],
            ['name' => 'Maya Sterling', 'photo_path' => 'actors/maya-sterling.jpg'],
            ['name' => 'Noah Wilder', 'photo_path' => 'actors/noah-wilder.jpg'],
            ['name' => 'Orla Finch', 'photo_path' => 'actors/orla-finch.jpg'],
        ];

        $users = [
            [
                'username' => 'admin',
                'name' => 'Administrator',
                'email' => 'admin@elitisripiw.test',
                'password' => Hash::make('password'),
                'profile_photo' => 'users/admin.jpg',
                'bio' => 'Admin akun demo untuk mengelola katalog film.',
                'role' => 'admin',
                'email_verified_at' => $now,
            ],
            [
                'username' => 'nabil',
                'name' => 'Nabil Pratama',
                'email' => 'nabil@example.test',
                'password' => Hash::make('password'),
                'profile_photo' => 'users/nabil.jpg',
                'bio' => 'Suka film sci-fi dan thriller.',
                'role' => 'user',
                'email_verified_at' => $now,
            ],
            [
                'username' => 'siska',
                'name' => 'Siska Amelia',
                'email' => 'siska@example.test',
                'password' => Hash::make('password'),
                'profile_photo' => 'users/siska.jpg',
                'bio' => 'Nonton film buat cari plot twist yang masuk akal, langka memang.',
                'role' => 'user',
                'email_verified_at' => $now,
            ],
            [
                'username' => 'raka',
                'name' => 'Raka Mahendra',
                'email' => 'raka@example.test',
                'password' => Hash::make('password'),
                'profile_photo' => 'users/raka.jpg',
                'bio' => 'Fans action dan crime.',
                'role' => 'user',
                'email_verified_at' => $now,
            ],
            [
                'username' => 'tania',
                'name' => 'Tania Putri',
                'email' => 'tania@example.test',
                'password' => Hash::make('password'),
                'profile_photo' => 'users/tania.jpg',
                'bio' => 'Lebih suka drama, romance, dan animasi.',
                'role' => 'user',
                'email_verified_at' => $now,
            ],
            [
                'username' => 'dimas',
                'name' => 'Dimas Prakoso',
                'email' => 'dimas@example.test',
                'password' => Hash::make('password'),
                'profile_photo' => 'users/dimas.jpg',
                'bio' => 'Kadang suka horror, lalu menyesal sendiri.',
                'role' => 'user',
                'email_verified_at' => $now,
            ],
        ];

        $movies = [
            [
                'title' => 'Neon Outbreak',
                'description' => 'Seorang teknisi jaringan menemukan konspirasi yang mengubah kota futuristik menjadi ladang perang digital.',
                'release_year' => 2024,
                'duration_minutes' => 128,
                'poster_path' => 'movies/neon-outbreak-poster.jpg',
                'banner_path' => 'movies/neon-outbreak-banner.jpg',
                'trailer_url' => 'https://www.youtube.com/watch?v=NeonOutbreakDemo1',
            ],
            [
                'title' => 'Paper Moon District',
                'description' => 'Dua saudara yang terpisah bertahun-tahun dipaksa bekerja sama saat sebuah kasus pembunuhan lama terbuka kembali.',
                'release_year' => 2023,
                'duration_minutes' => 117,
                'poster_path' => 'movies/paper-moon-district-poster.jpg',
                'banner_path' => 'movies/paper-moon-district-banner.jpg',
                'trailer_url' => 'https://www.youtube.com/watch?v=PaperMoonDistrictDemo1',
            ],
            [
                'title' => 'Echoes of Ember',
                'description' => 'Di dunia pasca-bencana, seorang penyintas mencari sumber api misterius yang bisa menyelamatkan koloni manusia terakhir.',
                'release_year' => 2025,
                'duration_minutes' => 141,
                'poster_path' => 'movies/echoes-of-ember-poster.jpg',
                'banner_path' => 'movies/echoes-of-ember-banner.jpg',
                'trailer_url' => 'https://www.youtube.com/watch?v=EchoesOfEmberDemo1',
            ],
            [
                'title' => 'The Last Lantern',
                'description' => 'Kisah perjalanan seorang kurir tua yang menjaga cahaya terakhir dari kota yang tak pernah tidur.',
                'release_year' => 2022,
                'duration_minutes' => 109,
                'poster_path' => 'movies/the-last-lantern-poster.jpg',
                'banner_path' => 'movies/the-last-lantern-banner.jpg',
                'trailer_url' => 'https://www.youtube.com/watch?v=TheLastLanternDemo1',
            ],
            [
                'title' => 'Static Bloom',
                'description' => 'Sebuah eksperimen pertanian vertikal berubah menjadi ancaman ketika tanaman bio-engineered mulai bereaksi pada emosi manusia.',
                'release_year' => 2024,
                'duration_minutes' => 124,
                'poster_path' => 'movies/static-bloom-poster.jpg',
                'banner_path' => 'movies/static-bloom-banner.jpg',
                'trailer_url' => 'https://www.youtube.com/watch?v=StaticBloomDemo1',
            ],
            [
                'title' => 'Velvet Equation',
                'description' => 'Seorang dosen matematika terseret ke dunia kriminal setelah menemukan pola dalam transaksi ilegal.',
                'release_year' => 2021,
                'duration_minutes' => 112,
                'poster_path' => 'movies/velvet-equation-poster.jpg',
                'banner_path' => 'movies/velvet-equation-banner.jpg',
                'trailer_url' => 'https://www.youtube.com/watch?v=VelvetEquationDemo1',
            ],
            [
                'title' => 'Mirage Harbor',
                'description' => 'Di kota pelabuhan yang penuh kabut, seorang jurnalis mengungkap rahasia identitas yang selama ini disembunyikan.',
                'release_year' => 2023,
                'duration_minutes' => 131,
                'poster_path' => 'movies/mirage-harbor-poster.jpg',
                'banner_path' => 'movies/mirage-harbor-banner.jpg',
                'trailer_url' => 'https://www.youtube.com/watch?v=MirageHarborDemo1',
            ],
            [
                'title' => 'Orbit of Ashes',
                'description' => 'Misi luar angkasa yang tampaknya rutin berubah jadi pertaruhan terakhir untuk pulang ke Bumi.',
                'release_year' => 2025,
                'duration_minutes' => 136,
                'poster_path' => 'movies/orbit-of-ashes-poster.jpg',
                'banner_path' => 'movies/orbit-of-ashes-banner.jpg',
                'trailer_url' => 'https://www.youtube.com/watch?v=OrbitOfAshesDemo1',
            ],
            [
                'title' => 'June Without Rain',
                'description' => 'Romansa lembut antara fotografer jalanan dan penulis yang sama-sama menolak masa lalu mereka.',
                'release_year' => 2020,
                'duration_minutes' => 104,
                'poster_path' => 'movies/june-without-rain-poster.jpg',
                'banner_path' => 'movies/june-without-rain-banner.jpg',
                'trailer_url' => 'https://www.youtube.com/watch?v=JuneWithoutRainDemo1',
            ],
            [
                'title' => 'Cinderline',
                'description' => 'Seorang detektif muda menyusuri kasus pembakaran berantai yang selalu meninggalkan simbol aneh.',
                'release_year' => 2022,
                'duration_minutes' => 118,
                'poster_path' => 'movies/cinderline-poster.jpg',
                'banner_path' => 'movies/cinderline-banner.jpg',
                'trailer_url' => 'https://www.youtube.com/watch?v=CinderlineDemo1',
            ],
            [
                'title' => 'Moonlit Cartel',
                'description' => 'Perang tak terlihat antara keluarga kriminal dan aparat di kota pesisir yang glamor namun rapuh.',
                'release_year' => 2024,
                'duration_minutes' => 133,
                'poster_path' => 'movies/moonlit-cartel-poster.jpg',
                'banner_path' => 'movies/moonlit-cartel-banner.jpg',
                'trailer_url' => 'https://www.youtube.com/watch?v=MoonlitCartelDemo1',
            ],
            [
                'title' => 'Paper Sky Parade',
                'description' => 'Film animasi tentang sebuah parade terbang yang mempertemukan anak-anak dari berbagai penjuru kota.',
                'release_year' => 2021,
                'duration_minutes' => 96,
                'poster_path' => 'movies/paper-sky-parade-poster.jpg',
                'banner_path' => 'movies/paper-sky-parade-banner.jpg',
                'trailer_url' => 'https://www.youtube.com/watch?v=PaperSkyParadeDemo1',
            ],
            [
                'title' => 'Mosaic of Silence',
                'description' => 'Seorang psikiater menyelami ingatan pasiennya dan mendapati jejak tragedi yang ia sendiri pernah alami.',
                'release_year' => 2023,
                'duration_minutes' => 126,
                'poster_path' => 'movies/mosaic-of-silence-poster.jpg',
                'banner_path' => 'movies/mosaic-of-silence-banner.jpg',
                'trailer_url' => 'https://www.youtube.com/watch?v=MosaicOfSilenceDemo1',
            ],
            [
                'title' => 'Glass Harbor',
                'description' => 'Drama keluarga tentang nelayan modern yang bertahan hidup di tengah investasi besar-besaran.',
                'release_year' => 2020,
                'duration_minutes' => 121,
                'poster_path' => 'movies/glass-harbor-poster.jpg',
                'banner_path' => 'movies/glass-harbor-banner.jpg',
                'trailer_url' => 'https://www.youtube.com/watch?v=GlassHarborDemo1',
            ],
            [
                'title' => 'Warden of Hollow Street',
                'description' => 'Ketika lingkungan tempat tinggalnya berubah menjadi area tak aman, seorang pemuda memutuskan melawan sendirian.',
                'release_year' => 2024,
                'duration_minutes' => 129,
                'poster_path' => 'movies/warden-of-hollow-street-poster.jpg',
                'banner_path' => 'movies/warden-of-hollow-street-banner.jpg',
                'trailer_url' => 'https://www.youtube.com/watch?v=WardenOfHollowStreetDemo1',
            ],
            [
                'title' => 'Flicker House',
                'description' => 'Sebuah rumah tua menyimpan rekaman film yang selalu berubah ketika diputar tengah malam.',
                'release_year' => 2022,
                'duration_minutes' => 115,
                'poster_path' => 'movies/flicker-house-poster.jpg',
                'banner_path' => 'movies/flicker-house-banner.jpg',
                'trailer_url' => 'https://www.youtube.com/watch?v=FlickerHouseDemo1',
            ],
            [
                'title' => 'Starlight Denial',
                'description' => 'Aksi dan drama berputar di sekitar astronaut yang menolak perintah untuk meninggalkan kru-nya.',
                'release_year' => 2025,
                'duration_minutes' => 140,
                'poster_path' => 'movies/starlight-denial-poster.jpg',
                'banner_path' => 'movies/starlight-denial-banner.jpg',
                'trailer_url' => 'https://www.youtube.com/watch?v=StarlightDenialDemo1',
            ],
            [
                'title' => 'Crimson Pantry',
                'description' => 'Komedi gelap tentang restoran kecil yang mendadak terkenal setelah menu rahasianya bocor ke internet.',
                'release_year' => 2021,
                'duration_minutes' => 101,
                'poster_path' => 'movies/crimson-pantry-poster.jpg',
                'banner_path' => 'movies/crimson-pantry-banner.jpg',
                'trailer_url' => 'https://www.youtube.com/watch?v=CrimsonPantryDemo1',
            ],
            [
                'title' => 'Tomorrow in Fragments',
                'description' => 'Eksperimen waktu yang gagal membuat satu keluarga terjebak dalam versi hari yang selalu berbeda.',
                'release_year' => 2024,
                'duration_minutes' => 132,
                'poster_path' => 'movies/tomorrow-in-fragments-poster.jpg',
                'banner_path' => 'movies/tomorrow-in-fragments-banner.jpg',
                'trailer_url' => 'https://www.youtube.com/watch?v=TomorrowInFragmentsDemo1',
            ],
            [
                'title' => 'Harbor of Glass Wolves',
                'description' => 'Petualangan fantasi tentang pemburu bayaran yang melindungi makhluk langka dari pasar gelap.',
                'release_year' => 2023,
                'duration_minutes' => 127,
                'poster_path' => 'movies/harbor-of-glass-wolves-poster.jpg',
                'banner_path' => 'movies/harbor-of-glass-wolves-banner.jpg',
                'trailer_url' => 'https://www.youtube.com/watch?v=HarborOfGlassWolvesDemo1',
            ],
        ];

        DB::table('genres')->insert($genres);
        DB::table('directors')->insert($directors);
        DB::table('actors')->insert($actors);
        DB::table('users')->insert($users);
        DB::table('movies')->insert($movies);

        $genreIds = DB::table('genres')->pluck('id', 'name');
        $directorIds = DB::table('directors')->pluck('id', 'name');
        $actorIds = DB::table('actors')->pluck('id', 'name');
        $userIds = DB::table('users')->pluck('id', 'username');
        $movieIds = DB::table('movies')->pluck('id', 'title');

        $movieGenres = [
            'Neon Outbreak' => ['Sci-Fi', 'Action', 'Thriller'],
            'Paper Moon District' => ['Drama', 'Mystery', 'Crime'],
            'Echoes of Ember' => ['Sci-Fi', 'Adventure', 'Drama'],
            'The Last Lantern' => ['Drama', 'Fantasy'],
            'Static Bloom' => ['Sci-Fi', 'Horror', 'Thriller'],
            'Velvet Equation' => ['Crime', 'Drama', 'Mystery'],
            'Mirage Harbor' => ['Mystery', 'Thriller', 'Drama'],
            'Orbit of Ashes' => ['Sci-Fi', 'Action', 'Adventure'],
            'June Without Rain' => ['Romance', 'Drama'],
            'Cinderline' => ['Crime', 'Thriller', 'Mystery'],
            'Moonlit Cartel' => ['Crime', 'Action', 'Drama'],
            'Paper Sky Parade' => ['Animation', 'Adventure', 'Comedy'],
            'Mosaic of Silence' => ['Drama', 'Mystery', 'Thriller'],
            'Glass Harbor' => ['Drama', 'Crime'],
            'Warden of Hollow Street' => ['Action', 'Crime', 'Thriller'],
            'Flicker House' => ['Horror', 'Mystery'],
            'Starlight Denial' => ['Sci-Fi', 'Action', 'Drama'],
            'Crimson Pantry' => ['Comedy', 'Drama'],
            'Tomorrow in Fragments' => ['Sci-Fi', 'Mystery', 'Drama'],
            'Harbor of Glass Wolves' => ['Fantasy', 'Adventure', 'Action'],
        ];

        $movieDirectors = [
            'Neon Outbreak' => 'Alden Hart',
            'Paper Moon District' => 'Mira Solano',
            'Echoes of Ember' => 'Darren Vale',
            'The Last Lantern' => 'Nadia Rook',
            'Static Bloom' => 'Ethan Cross',
            'Velvet Equation' => 'Lena Ward',
            'Mirage Harbor' => 'Rafiq Stone',
            'Orbit of Ashes' => 'Juniper Blake',
            'June Without Rain' => 'Mira Solano',
            'Cinderline' => 'Alden Hart',
            'Moonlit Cartel' => 'Darren Vale',
            'Paper Sky Parade' => 'Lena Ward',
            'Mosaic of Silence' => 'Nadia Rook',
            'Glass Harbor' => 'Rafiq Stone',
            'Warden of Hollow Street' => 'Ethan Cross',
            'Flicker House' => 'Juniper Blake',
            'Starlight Denial' => 'Alden Hart',
            'Crimson Pantry' => 'Mira Solano',
            'Tomorrow in Fragments' => 'Darren Vale',
            'Harbor of Glass Wolves' => 'Nadia Rook',
        ];

        $movieActors = [
            'Neon Outbreak' => [
                ['Aria Noland', 'Juno Vale'],
                ['Bram Kessler', 'Iris Bennett'],
                ['Cleo Mercer', 'Chief Analyst Sera'],
            ],
            'Paper Moon District' => [
                ['Dante Voss', 'Adrian'],
                ['Elena Quinn', 'Mara'],
                ['Felix Arden', 'Inspector Harlan'],
            ],
            'Echoes of Ember' => [
                ['Gina Ravin', 'Captain Elara'],
                ['Hugo Lark', 'Tobin'],
                ['Kaia Monroe', 'Nera'],
            ],
            'The Last Lantern' => [
                ['Iris Bennett', 'Mina'],
                ['Jasper Holt', 'Old Courier'],
                ['Luca Ferris', 'Watchman'],
            ],
            'Static Bloom' => [
                ['Maya Sterling', 'Dr. Nisa'],
                ['Noah Wilder', 'Renn'],
                ['Orla Finch', 'Ivy'],
            ],
            'Velvet Equation' => [
                ['Aria Noland', 'Dr. Elin'],
                ['Felix Arden', 'Garrick'],
                ['Kaia Monroe', 'Tessa'],
            ],
            'Mirage Harbor' => [
                ['Bram Kessler', 'Journalist Leon'],
                ['Elena Quinn', 'Siv'],
                ['Luca Ferris', 'Dockmaster'],
            ],
            'Orbit of Ashes' => [
                ['Dante Voss', 'Commander Rho'],
                ['Maya Sterling', 'Pilot Veya'],
                ['Jasper Holt', 'Engineer Joss'],
            ],
            'June Without Rain' => [
                ['Cleo Mercer', 'Alya'],
                ['Hugo Lark', 'Rowan'],
                ['Orla Finch', 'Mina'],
            ],
            'Cinderline' => [
                ['Gina Ravin', 'Detective Mara'],
                ['Noah Wilder', 'Finn'],
                ['Iris Bennett', 'Rhea'],
            ],
            'Moonlit Cartel' => [
                ['Aria Noland', 'Selene'],
                ['Bram Kessler', 'Marek'],
                ['Dante Voss', 'Lucan'],
            ],
            'Paper Sky Parade' => [
                ['Elena Quinn', 'Nina'],
                ['Kaia Monroe', 'Pico'],
                ['Orla Finch', 'Lumi'],
            ],
            'Mosaic of Silence' => [
                ['Felix Arden', 'Dr. Nadir'],
                ['Luca Ferris', 'Jonah'],
                ['Maya Sterling', 'Ivy'],
            ],
            'Glass Harbor' => [
                ['Gina Ravin', 'Rina'],
                ['Hugo Lark', 'Bastian'],
                ['Jasper Holt', 'Timo'],
            ],
            'Warden of Hollow Street' => [
                ['Aria Noland', 'Kade'],
                ['Noah Wilder', 'Rune'],
                ['Cleo Mercer', 'Mira'],
            ],
            'Flicker House' => [
                ['Dante Voss', 'Evan'],
                ['Elena Quinn', 'Lia'],
                ['Orla Finch', 'June'],
            ],
            'Starlight Denial' => [
                ['Bram Kessler', 'Commander Rian'],
                ['Kaia Monroe', 'Sol'],
                ['Luca Ferris', 'Hale'],
            ],
            'Crimson Pantry' => [
                ['Maya Sterling', 'Chef Rhea'],
                ['Jasper Holt', 'Taro'],
                ['Gina Ravin', 'Bella'],
            ],
            'Tomorrow in Fragments' => [
                ['Felix Arden', 'Theo'],
                ['Hugo Lark', 'Nolan'],
                ['Iris Bennett', 'Asha'],
            ],
            'Harbor of Glass Wolves' => [
                ['Cleo Mercer', 'Vera'],
                ['Dante Voss', 'Orin'],
                ['Noah Wilder', 'Kellan'],
            ],
        ];

        $reviewIds = [];
        $ratingRows = [];
        $watchlistRows = [];

        foreach ($movies as $index => $movie) {
            $title = $movie['title'];
            $movieId = $movieIds[$title];

            foreach ($movieGenres[$title] as $genreName) {
                DB::table('movie_genres')->insert([
                    'movie_id' => $movieId,
                    'genre_id' => $genreIds[$genreName],
                ]);
            }

            DB::table('movie_directors')->insert([
                'movie_id' => $movieId,
                'director_id' => $directorIds[$movieDirectors[$title]],
            ]);

            foreach ($movieActors[$title] as $actorRow) {
                DB::table('movie_actors')->insert([
                    'movie_id' => $movieId,
                    'actor_id' => $actorIds[$actorRow[0]],
                    'role_name' => $actorRow[1],
                ]);
            }
        }

        $reviewData = [
            ['user' => 'nabil', 'movie' => 'Neon Outbreak', 'text' => 'Tempo cepat, visual futuristik, dan konflik utamanya cukup rapi. Lumayan bikin lupa buka tab lain.'],
            ['user' => 'siska', 'movie' => 'Paper Moon District', 'text' => 'Nuansanya gelap dan dialognya enak diikuti. Twist-nya tidak norak, itu sudah bonus besar.'],
            ['user' => 'raka', 'movie' => 'Orbit of Ashes', 'text' => 'Aksinya solid dan tekanan di ruang angkasa terasa. Bagus buat pecinta tension tinggi.'],
            ['user' => 'tania', 'movie' => 'June Without Rain', 'text' => 'Filmnya lembut, manis, dan punya pacing yang nyaman. Cocok kalau lagi capek sama plot yang ribut.'],
            ['user' => 'dimas', 'movie' => 'Flicker House', 'text' => 'Atmosfer horornya jalan, walau saya tetap menonton sambil menegur diri sendiri.'],
            ['user' => 'nabil', 'movie' => 'Mosaic of Silence', 'text' => 'Cerita psikologisnya kuat dan cukup bikin mikir. Tidak semua film perlu teriak-teriak untuk terasa berat.'],
            ['user' => 'siska', 'movie' => 'Crimson Pantry', 'text' => 'Komedi gelapnya pas dan karakternya menyenangkan. Jauh lebih hidup daripada rapat kelompok yang tak kunjung selesai.'],
            ['user' => 'raka', 'movie' => 'Moonlit Cartel', 'text' => 'Crime drama yang rapi, intens, dan punya visual kota yang meyakinkan.'],
            ['user' => 'tania', 'movie' => 'Paper Sky Parade', 'text' => 'Animasinya imut dan penuh warna. Sangat aman untuk hari yang ingin damai.'],
            ['user' => 'dimas', 'movie' => 'Harbor of Glass Wolves', 'text' => 'Fantasinya cukup unik dan world-building-nya menarik. Saya ingin lihat versi serialnya.'],
        ];

        foreach ($reviewData as $i => $review) {
            DB::table('reviews')->insert([
                'user_id' => $userIds[$review['user']],
                'movie_id' => $movieIds[$review['movie']],
                'review_text' => $review['text'],
                'created_at' => $now->copy()->subDays(10 - $i),
                'updated_at' => $now->copy()->subDays(10 - $i),
            ]);
        }

        $reviewIds = DB::table('reviews')->pluck('id')->values();

        $ratingsData = [
            ['user' => 'nabil', 'movie' => 'Neon Outbreak', 'score' => 9],
            ['user' => 'siska', 'movie' => 'Paper Moon District', 'score' => 8],
            ['user' => 'raka', 'movie' => 'Orbit of Ashes', 'score' => 9],
            ['user' => 'tania', 'movie' => 'June Without Rain', 'score' => 8],
            ['user' => 'dimas', 'movie' => 'Flicker House', 'score' => 7],
            ['user' => 'nabil', 'movie' => 'Mosaic of Silence', 'score' => 8],
            ['user' => 'siska', 'movie' => 'Crimson Pantry', 'score' => 9],
            ['user' => 'raka', 'movie' => 'Moonlit Cartel', 'score' => 8],
            ['user' => 'tania', 'movie' => 'Paper Sky Parade', 'score' => 9],
            ['user' => 'dimas', 'movie' => 'Harbor of Glass Wolves', 'score' => 8],
            ['user' => 'nabil', 'movie' => 'Starlight Denial', 'score' => 9],
            ['user' => 'siska', 'movie' => 'Static Bloom', 'score' => 7],
            ['user' => 'raka', 'movie' => 'Velvet Equation', 'score' => 8],
            ['user' => 'tania', 'movie' => 'Mirage Harbor', 'score' => 8],
            ['user' => 'dimas', 'movie' => 'The Last Lantern', 'score' => 8],
            ['user' => 'nabil', 'movie' => 'Cinderline', 'score' => 7],
            ['user' => 'siska', 'movie' => 'Tomorrow in Fragments', 'score' => 9],
            ['user' => 'raka', 'movie' => 'Glass Harbor', 'score' => 7],
            ['user' => 'tania', 'movie' => 'Warden of Hollow Street', 'score' => 8],
            ['user' => 'dimas', 'movie' => 'Echoes of Ember', 'score' => 9],
        ];

        foreach ($ratingsData as $i => $rating) {
            DB::table('ratings')->insert([
                'user_id' => $userIds[$rating['user']],
                'movie_id' => $movieIds[$rating['movie']],
                'score' => $rating['score'],
                'created_at' => $now->copy()->subDays(20 - $i),
            ]);
        }

        $watchlistsData = [
            ['user' => 'nabil', 'movie' => 'Neon Outbreak', 'status' => 'completed'],
            ['user' => 'nabil', 'movie' => 'Orbit of Ashes', 'status' => 'watching'],
            ['user' => 'siska', 'movie' => 'Paper Moon District', 'status' => 'completed'],
            ['user' => 'siska', 'movie' => 'Mosaic of Silence', 'status' => 'plan_to_watch'],
            ['user' => 'raka', 'movie' => 'Moonlit Cartel', 'status' => 'watching'],
            ['user' => 'raka', 'movie' => 'Warden of Hollow Street', 'status' => 'plan_to_watch'],
            ['user' => 'tania', 'movie' => 'June Without Rain', 'status' => 'completed'],
            ['user' => 'tania', 'movie' => 'Paper Sky Parade', 'status' => 'watching'],
            ['user' => 'dimas', 'movie' => 'Flicker House', 'status' => 'watching'],
            ['user' => 'dimas', 'movie' => 'Harbor of Glass Wolves', 'status' => 'plan_to_watch'],
        ];

        foreach ($watchlistsData as $i => $watch) {
            DB::table('watchlists')->insert([
                'user_id' => $userIds[$watch['user']],
                'movie_id' => $movieIds[$watch['movie']],
                'status' => $watch['status'],
                'created_at' => $now->copy()->subDays(5 - $i),
                'updated_at' => $now->copy()->subDays(5 - $i),
            ]);
        }

        $reviewLikeData = [
            ['user' => 'siska', 'review_index' => 0],
            ['user' => 'raka', 'review_index' => 0],
            ['user' => 'nabil', 'review_index' => 1],
            ['user' => 'tania', 'review_index' => 2],
            ['user' => 'dimas', 'review_index' => 3],
            ['user' => 'nabil', 'review_index' => 4],
            ['user' => 'siska', 'review_index' => 5],
            ['user' => 'raka', 'review_index' => 6],
            ['user' => 'tania', 'review_index' => 7],
            ['user' => 'dimas', 'review_index' => 8],
            ['user' => 'nabil', 'review_index' => 9],
        ];

        foreach ($reviewLikeData as $i => $like) {
            DB::table('review_likes')->insert([
                'user_id' => $userIds[$like['user']],
                'review_id' => $reviewIds[$like['review_index']],
                'created_at' => $now->copy()->subDays($i),
            ]);
        }
    }
}
