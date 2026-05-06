<?php

// Namespace untuk semua controller HTTP aplikasi.
namespace App\Http\Controllers;

// Model Genre dipakai untuk mengisi opsi filter genre.
use App\Models\Genre;
// Model Movie dipakai untuk query hasil pencarian dan suggestion.
use App\Models\Movie;
// Facade untuk cek status login dan ambil ID user.
use Illuminate\Support\Facades\Auth;
// Tipe response JSON untuk endpoint suggest.
use Illuminate\Http\JsonResponse;
// Objek request HTTP yang membawa query string dari user.
use Illuminate\Http\Request;

// Controller yang menangani halaman search dan endpoint live suggestion.
class SearchController extends Controller
{
    // Menampilkan halaman hasil pencarian lengkap dengan filter.
    public function search(Request $request)
    {
        // Ambil keyword dari query string q, cast ke string, lalu trim spasi.
        $q = trim((string) $request->query('q', ''));

        // Kumpulkan nilai filter dari URL agar mudah dipakai di scope filter.
        $filters = [
            // ID genre terpilih (boleh null/kosong).
            'genre_id' => $request->query('genre_id'),
            // Tahun rilis terpilih (boleh null/kosong).
            'release_year' => $request->query('release_year'),
            // Nilai skor minimum terpilih (boleh null/kosong).
            'min_score' => $request->query('min_score'),
        ];

        // Bangun query utama untuk daftar film pada halaman search.
        $movies = Movie::query()
            // Jika user login, preload status watchlist user untuk ditampilkan di card.
            ->when(Auth::check(), function ($query) {
                return $query->withWatchlistForUser(Auth::id());
            })
            // Preload statistik rating (avg dan count) untuk sort/tampilan.
            ->withRatingsStats()
            // Preload relasi genre agar tidak N+1 query di view.
            ->with(['genres:id,name'])
            // Terapkan pencarian judul berdasarkan keyword q.
            ->search($q)
            // Terapkan filter lanjutan: genre, tahun rilis, min score.
            ->filter($filters)
            // Urutkan prioritas 1: skor rata-rata tertinggi.
            ->orderByDesc('ratings_avg_score')
            // Urutkan prioritas 2: jumlah rating terbanyak.
            ->orderByDesc('ratings_count')
            // Paginate 12 item per halaman.
            ->paginate(12)
            // Pertahankan query string saat pindah halaman pagination.
            ->withQueryString();

        // Ambil semua genre untuk dropdown filter genre di UI.
        $genres = Genre::query()->orderBy('name')->get(['id', 'name']);
        // Ambil daftar tahun rilis unik untuk dropdown filter tahun.
        $releaseYears = Movie::query()
            // Hanya ambil data yang punya tahun rilis.
            ->whereNotNull('release_year')
            // Pilih kolom release_year saja agar query ringan.
            ->select('release_year')
            // Hilangkan duplikasi tahun.
            ->distinct()
            // Urutkan tahun terbaru ke terlama.
            ->orderByDesc('release_year')
            // Ambil sebagai koleksi nilai release_year.
            ->pluck('release_year');

        // Kirim seluruh data yang dibutuhkan view search.
        return view('search', [
            // Data hasil pencarian yang sudah dipaginate.
            'movies' => $movies,
            // Data genre untuk filter dropdown.
            'genres' => $genres,
            // Data tahun rilis unik untuk filter dropdown.
            'releaseYears' => $releaseYears,
            // Keyword aktif agar input search tetap terisi.
            'q' => $q,
            // Simpan state filter genre terpilih di UI.
            'selectedGenreId' => $filters['genre_id'],
            // Simpan state filter tahun terpilih di UI.
            'selectedReleaseYear' => $filters['release_year'],
            // Simpan state filter skor minimum terpilih di UI.
            'selectedMinScore' => $filters['min_score'],
        ]);
    }

    // Endpoint JSON untuk live search suggestion di navbar.
    public function suggest(Request $request): JsonResponse
    {
        // Ambil query q dari URL, cast ke string, lalu trim spasi.
        $q = trim((string) $request->query('q', ''));

        // Jika query terlalu pendek, jangan query DB; kembalikan data kosong.
        if (mb_strlen($q) < 2) {
            return response()->json([
                // Data suggestion kosong karena belum memenuhi minimal karakter.
                'data' => [],
                // Tetap kirim URL "see more" agar UI punya fallback navigasi.
                'see_more_url' => route('search', ['q' => $q]),
            ]);
        }

        // Query suggestion: ringan, terurut, dan dibatasi agar cepat.
        $movies = Movie::query()
            // Pilih kolom yang diperlukan saja untuk suggestion card.
            ->select([
                'id',
                'title',
                'release_year',
                'poster_path',
                'duration_minutes',
            ])
            // Terapkan pencarian judul berdasarkan keyword q.
            ->search($q)
            // Ambil statistik rating untuk sorting dan display skor.
            ->withRatingsStats()
            // Prioritaskan film dengan skor rata-rata tertinggi.
            ->orderByDesc('ratings_avg_score')
            // Jika skor sama, prioritaskan yang jumlah ratingnya lebih banyak.
            ->orderByDesc('ratings_count')
            // Batasi hanya 3 item agar respons cepat untuk live search.
            ->limit(3)
            // Eksekusi query dan ambil koleksi hasil.
            ->get();

        // Kembalikan payload JSON dalam format yang siap dirender frontend.
        return response()->json([
            // Mapping model Movie ke struktur data ringkas untuk dropdown suggestion.
            'data' => $movies->map(function (Movie $movie) {
                return [
                    // ID unik film.
                    'id' => $movie->id,
                    // Judul film.
                    'title' => $movie->title,
                    // Tahun rilis (bisa null jika data tidak ada).
                    'release_year' => $movie->release_year,
                    // Durasi terformat lewat accessor model.
                    'duration' => $movie->duration_formatted,
                    // URL poster final lewat accessor model.
                    'poster_url' => $movie->poster_url,
                    // Skor rata-rata diformat 1 angka desimal untuk UI.
                    'average_score' => number_format($movie->average_score, 1),
                    // URL ke halaman detail film.
                    'url' => route('movies.show', $movie),
                ];
            // Reindex koleksi agar menjadi array numerik berurutan.
            })->values(),
            // URL ke halaman search penuh dengan query yang sama.
            'see_more_url' => route('search', ['q' => $q]),
        ]);
    }
}
