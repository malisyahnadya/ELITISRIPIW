<?php

// Namespace untuk command import TMDB movie.
namespace App\Console\Commands;

// Model actor dipakai untuk menyimpan data pemeran.
use App\Models\Actor;
// Model director dipakai untuk menyimpan data sutradara.
use App\Models\Director;
// Model genre dipakai untuk menyimpan data genre.
use App\Models\Genre;
// Model movie dipakai sebagai data utama film.
use App\Models\Movie;
// Base class untuk command artisan.
use Illuminate\Console\Command;
// HTTP client Laravel untuk request ke TMDB API.
use Illuminate\Support\Facades\Http;

// Class command untuk import movie dari TMDB.
class ImportMovieFromTmdb extends Command
{
    // Signature command artisan beserta argument dan option-nya.
    protected $signature = 'tmdb:import-movie
                            {query : Movie title or TMDB ID}
                            {--id : Treat query as TMDB ID}
                            {--overwrite : Overwrite existing data}
                            {--cast-limit=12 : Number of cast members to import}';

    // Deskripsi command yang tampil di artisan.
    protected $description = 'Import movie details, poster, banner, trailer, genres, actors, and directors from TMDB';

    // Method utama yang dijalankan saat command dipanggil.
    public function handle(): int
    {
        // Ambil API key TMDB dari config atau environment.
        $apiKey = config('services.tmdb.key') ?: env('TMDB_API_KEY');

        // Kalau API key belum ada, hentikan proses.
        if (!$apiKey) {
            // Tampilkan pesan error ke console.
            $this->error('TMDB_API_KEY belum diisi di .env');
            // Kembalikan status gagal.
            return self::FAILURE;
        }

        // Ambil input query dari argument command.
        $query = (string) $this->argument('query');
        // Cek apakah query dianggap sebagai ID TMDB.
        $isId = $this->option('id') || ctype_digit($query);

        // Jika query berupa ID, pakai langsung; kalau bukan, cari ID dari judul.
        $tmdbId = $isId ? (int) $query : $this->searchMovieId($query);

        // Jika movie tidak ditemukan, hentikan proses.
        if (!$tmdbId) {
            // Tampilkan error bahwa movie tidak ditemukan.
            $this->error("Movie tidak ditemukan di TMDB: {$query}");
            // Kembalikan status gagal.
            return self::FAILURE;
        }

        // Ambil detail movie lengkap dari TMDB.
        $details = $this->getMovieDetails($tmdbId);

        // Jika detail gagal diambil, hentikan proses.
        if (!$details) {
            // Tampilkan error ke console.
            $this->error("Gagal mengambil detail movie dari TMDB.");
            // Kembalikan status gagal.
            return self::FAILURE;
        }

        // Simpan atau update data utama movie ke database.
        $movie = $this->saveMovie($details);

        // Sinkronkan genre movie ke database dan relasinya.
        $this->syncGenres($movie, $details);
        // Sinkronkan sutradara movie ke database dan relasinya.
        $this->syncDirectors($movie, $details);
        // Sinkronkan aktor movie ke database dan relasinya.
        $this->syncActors($movie, $details);

        // Tampilkan pesan sukses setelah semua data masuk.
        $this->info("Selesai import: {$movie->title}");

        // Kembalikan status sukses.
        return self::SUCCESS;
    }

    // Cari ID movie TMDB berdasarkan judul.
    private function searchMovieId(string $title): ?int
    {
        // Request ke endpoint pencarian movie TMDB.
        $response = $this->tmdbGet('/search/movie', [
            // Query pencarian judul.
            'query' => $title,
            // Hanya movie non dewasa.
            'include_adult' => false,
        ]);

        // Kalau request gagal atau response tidak sukses, kembalikan null.
        if (!$response || !$response->successful()) {
            return null;
        }

        // Ambil ID hasil pertama dari response TMDB.
        return $response->json('results.0.id');
    }

    // Ambil detail movie berdasarkan ID TMDB.
    private function getMovieDetails(int $tmdbId): ?array
    {
        // Request detail movie dan sertakan credits serta videos.
        $response = $this->tmdbGet('/movie/' . $tmdbId, [
            // Tambahkan data credits dan videos di response.
            'append_to_response' => 'credits,videos',
        ]);

        // Kalau request gagal atau response tidak sukses, kembalikan null.
        if (!$response || !$response->successful()) {
            return null;
        }

        // Kembalikan seluruh payload JSON dari TMDB.
        return $response->json();
    }

    // Simpan data utama movie ke tabel movies.
    private function saveMovie(array $details): Movie
    {
        // Ambil ID TMDB dari detail.
        $tmdbId = (int) $details['id'];
        // Tentukan judul movie dari field title atau original_title.
        $title = $details['title'] ?? $details['original_title'] ?? 'Untitled Movie';

        // Cari movie berdasarkan tmdb_id.
        $movie = Movie::where('tmdb_id', $tmdbId)->first();

        // Kalau belum ketemu, coba cari berdasarkan title.
        if (!$movie) {
            $movie = Movie::where('title', $title)->first();
        }

        // Kalau masih belum ketemu, buat instance baru.
        if (!$movie) {
            $movie = new Movie();
        }

        // Set tmdb_id agar movie terhubung ke data TMDB.
        $movie->tmdb_id = $tmdbId;

        // Isi title kalau diizinkan oleh aturan overwrite.
        $this->fillIfAllowed($movie, 'title', $title);
        // Isi description dari overview TMDB.
        $this->fillIfAllowed($movie, 'description', $details['overview'] ?? null);
        // Isi release_year dari tanggal rilis.
        $this->fillIfAllowed($movie, 'release_year', $this->releaseYear($details['release_date'] ?? null));
        // Isi durasi film dalam menit.
        $this->fillIfAllowed($movie, 'duration_minutes', $details['runtime'] ?? null);
        // Isi poster_path dengan URL poster TMDB.
        $this->fillIfAllowed($movie, 'poster_path', $this->imageUrl($details['poster_path'] ?? null, 'w500'));
        // Isi banner_path dengan URL backdrop TMDB.
        $this->fillIfAllowed($movie, 'banner_path', $this->imageUrl($details['backdrop_path'] ?? null, 'original'));
        // Isi trailer_url jika ada video trailer dari TMDB.
        $this->fillIfAllowed($movie, 'trailer_url', $this->trailerUrl($details['videos']['results'] ?? []));

        // Simpan movie ke database.
        $movie->save();

        // Tampilkan log bahwa movie sudah disimpan.
        $this->info("Movie saved: {$movie->title}");

        // Kembalikan object movie yang sudah tersimpan.
        return $movie;
    }

    // Sinkronkan genre movie ke database dan pivot relasinya.
    private function syncGenres(Movie $movie, array $details): void
    {
        // Tampung ID genre yang berhasil diproses.
        $genreIds = [];

        // Loop semua genre dari response TMDB.
        foreach (($details['genres'] ?? []) as $genreData) {
            // Kalau nama genre kosong, lewati.
            if (empty($genreData['name'])) {
                continue;
            }

            // Cari genre berdasarkan nama, atau buat baru jika belum ada.
            $genre = Genre::firstOrCreate([
                'name' => $genreData['name'],
            ]);

            // Simpan ID genre untuk disinkronkan ke movie.
            $genreIds[] = $genre->id;
        }

        // Hubungkan movie dengan daftar genre yang ditemukan.
        $movie->genres()->sync($genreIds);

        // Tampilkan jumlah genre yang disinkronkan.
        $this->info('Genres synced: ' . count($genreIds));
    }

    // Sinkronkan director movie ke database dan pivot relasinya.
    private function syncDirectors(Movie $movie, array $details): void
    {
        // Tampung ID director yang diproses.
        $directorIds = [];

        // Ambil crew yang job-nya Director.
        $crew = collect($details['credits']['crew'] ?? [])
            ->where('job', 'Director')
            ->values();

        // Loop setiap director yang ditemukan.
        foreach ($crew as $person) {
            // Kalau nama kosong, lewati.
            if (empty($person['name'])) {
                continue;
            }

            // Cari director berdasarkan tmdb_id.
            $director = Director::where('tmdb_id', $person['id'] ?? null)->first();

            // Kalau belum ada, cari berdasarkan nama.
            if (!$director) {
                $director = Director::where('name', $person['name'])->first();
            }

            // Kalau masih belum ada, buat data baru.
            if (!$director) {
                $director = new Director();
            }

            // Set tmdb_id director.
            $director->tmdb_id = $person['id'] ?? null;
            // Set nama director.
            $director->name = $person['name'];

            // Isi foto jika overwrite aktif atau foto masih kosong.
            if ($this->option('overwrite') || blank($director->photo_path)) {
                $director->photo_path = $this->imageUrl($person['profile_path'] ?? null, 'w185');
            }

            // Simpan director ke database.
            $director->save();

            // Catat ID director untuk pivot relasi.
            $directorIds[] = $director->id;
        }

        // Hubungkan movie ke daftar director.
        $movie->directors()->sync($directorIds);

        // Tampilkan jumlah director yang disinkronkan.
        $this->info('Directors synced: ' . count($directorIds));
    }

    // Sinkronkan actor/cast movie ke database dan pivot relasinya.
    private function syncActors(Movie $movie, array $details): void
    {
        // Array untuk menyimpan pivot actor ke movie.
        $actorSync = [];
        // Ambil limit cast dari option command.
        $limit = (int) $this->option('cast-limit');

        // Ambil daftar cast dan batasi sesuai limit.
        $cast = collect($details['credits']['cast'] ?? [])
            ->take($limit)
            ->values();

        // Loop setiap aktor dalam cast.
        foreach ($cast as $person) {
            // Kalau nama kosong, lewati.
            if (empty($person['name'])) {
                continue;
            }

            // Cari actor berdasarkan tmdb_id.
            $actor = Actor::where('tmdb_id', $person['id'] ?? null)->first();

            // Kalau belum ada, cari berdasarkan nama.
            if (!$actor) {
                $actor = Actor::where('name', $person['name'])->first();
            }

            // Kalau masih belum ada, buat data baru.
            if (!$actor) {
                $actor = new Actor();
            }

            // Set tmdb_id actor.
            $actor->tmdb_id = $person['id'] ?? null;
            // Set nama actor.
            $actor->name = $person['name'];

            // Isi foto jika overwrite aktif atau foto masih kosong.
            if ($this->option('overwrite') || blank($actor->photo_path)) {
                $actor->photo_path = $this->imageUrl($person['profile_path'] ?? null, 'w185');
            }

            // Simpan actor ke database.
            $actor->save();

            // Simpan relasi actor ke movie beserta nama perannya.
            $actorSync[$actor->id] = [
                'role_name' => $person['character'] ?? null,
            ];
        }

        // Sinkronkan pivot relasi actor ke movie.
        $movie->actors()->sync($actorSync);

        // Tampilkan jumlah actor yang disinkronkan.
        $this->info('Actors synced: ' . count($actorSync));
    }

    // Isi field movie hanya jika diizinkan.
    private function fillIfAllowed(Movie $movie, string $field, mixed $value): void
    {
        // Kalau value kosong, tidak perlu diisi.
        if ($value === null || $value === '') {
            return;
        }

        // Kalau overwrite aktif atau field masih kosong, isi field tersebut.
        if ($this->option('overwrite') || blank($movie->{$field})) {
            $movie->{$field} = $value;
        }
    }

    // Cari URL trailer YouTube terbaik dari daftar video.
    private function trailerUrl(array $videos): ?string
    {
        // Ubah array video jadi collection untuk difilter.
        $collection = collect($videos);

        // Cari trailer official dari YouTube.
        $video = $collection
            ->where('site', 'YouTube')
            ->where('type', 'Trailer')
            ->where('official', true)
            ->first();

        // Kalau belum ketemu, cari trailer YouTube biasa.
        if (!$video) {
            $video = $collection
                ->where('site', 'YouTube')
                ->where('type', 'Trailer')
                ->first();
        }

        // Kalau masih belum ketemu, ambil video YouTube pertama.
        if (!$video) {
            $video = $collection
                ->where('site', 'YouTube')
                ->first();
        }

        // Kalau key video kosong, return null.
        if (!$video || empty($video['key'])) {
            return null;
        }

        // Bangun URL YouTube dari key video.
        return 'https://www.youtube.com/watch?v=' . $video['key'];
    }

    // Ubah path TMDB menjadi URL penuh gambar.
    private function imageUrl(?string $path, string $size): ?string
    {
        // Kalau path kosong, tidak ada URL.
        if (!$path) {
            return null;
        }

        // Kalau path sudah URL penuh, kembalikan apa adanya.
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        // Gabungkan base URL TMDB image dengan size dan path.
        return 'https://image.tmdb.org/t/p/' . trim($size, '/') . $path;
    }

    // Ambil tahun rilis dari tanggal rilis.
    private function releaseYear(?string $releaseDate): ?int
    {
        // Kalau tanggal kosong atau tidak valid, return null.
        if (!$releaseDate || strlen($releaseDate) < 4) {
            return null;
        }

        // Ambil 4 karakter pertama sebagai tahun.
        return (int) substr($releaseDate, 0, 4);
    }

    // Helper untuk request ke TMDB API.
    private function tmdbGet(string $path, array $params = [])
    {
        // Jalankan GET request ke endpoint TMDB dengan parameter default.
        return Http::get(
            // Base URL TMDB lalu digabung dengan path endpoint.
            rtrim(config('services.tmdb.base_url', 'https://api.themoviedb.org/3'), '/') . $path,
            // Gabungkan parameter default dengan parameter spesifik request.
            array_merge([
                // API key untuk autentikasi.
                'api_key' => config('services.tmdb.key') ?: env('TMDB_API_KEY'),
                // Bahasa response dari TMDB.
                'language' => config('services.tmdb.language', 'en-US'),
            ], $params)
        );
    }
}