<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use App\Models\Movie;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    // Metode untuk melakukan pencarian film berdasarkan kriteria tertentu.
    public function search(Request $request)
    {
        $q = trim((string) $request->query('q', ''));

        // Mengumpulkan filter yang diterapkan dari query string
        $filters = [
            'genre_id' => $request->query('genre_id'),
            'release_year' => $request->query('release_year'),
            'min_score' => $request->query('min_score'),
        ];

        // Membangun query untuk mencari film berdasarkan judul dan filter yang diterapkan, serta memuat statistik rating untuk pengurutan.
        $movies = Movie::query()
            ->withRatingsStats()
            ->with(['genres:id,name'])
            ->search($q)
            ->filter($filters)
            ->orderByDesc('ratings_avg_score')
            ->orderByDesc('ratings_count')
            ->paginate(12)
            ->withQueryString();

        // Mengambil daftar genre dan tahun rilis yang tersedia untuk filter di view.
        $genres = Genre::query()->orderBy('name')->get(['id', 'name']);
        $releaseYears = Movie::query()
            ->whereNotNull('release_year')
            ->select('release_year')
            ->distinct()
            ->orderByDesc('release_year')
            ->pluck('release_year');

        // Mengembalikan view dengan data film hasil pencarian, daftar genre, tahun rilis, dan nilai filter yang diterapkan untuk menjaga state di UI.
            return view('search', [
            'movies' => $movies,
            'genres' => $genres,
            'releaseYears' => $releaseYears,
            'q' => $q,
            'selectedGenreId' => $filters['genre_id'],
            'selectedReleaseYear' => $filters['release_year'],
            'selectedMinScore' => $filters['min_score'],
        ]);
    }

    
    // Metode untuk memberikan saran pencarian berdasarkan input pengguna.
    public function suggest(Request $request): JsonResponse
    {
        // Mengambil query pencarian dari parameter 'q' dan memastikan panjangnya minimal 2 karakter untuk menghindari hasil yang terlalu luas.
        $q = trim((string) $request->query('q', ''));

        // Jika query terlalu pendek, kembalikan respons kosong dengan URL untuk melihat hasil pencarian lengkap.
        if (mb_strlen($q) < 2) {
            return response()->json([
                'data' => [],
                'see_more_url' => route('search', ['q' => $q]),
            ]);
        }

        // Mencari film yang judulnya mirip dengan query, memuat statistik rating untuk pengurutan, dan membatasi hasil hanya 3 film untuk saran cepat.
        $movies = Movie::query()
            ->select([
                'id',
                'title',
                'release_year',
                'poster_path',
                'duration_minutes',
            ])
            ->search($q)
            ->withRatingsStats()
            ->orderByDesc('ratings_avg_score')
            ->orderByDesc('ratings_count')
            ->limit(3)
            ->get();

        // Mengembalikan respons JSON dengan data film yang ditemukan untuk saran pencarian, termasuk URL untuk melihat hasil pencarian lengkap jika pengguna ingin melihat lebih banyak hasil. Data film diformat untuk kebutuhan tampilan di frontend.
            return response()->json([
            'data' => $movies->map(function (Movie $movie) {
                return [
                    'id' => $movie->id,
                    'title' => $movie->title,
                    'release_year' => $movie->release_year,
                    'duration' => $movie->duration_formatted,
                    'poster_url' => $movie->poster_url,
                    'average_score' => number_format($movie->average_score, 1),
                    'url' => route('movies.show', $movie),
                ];
            })->values(),
            'see_more_url' => route('search', ['q' => $q]),
        ]);
    }
}
