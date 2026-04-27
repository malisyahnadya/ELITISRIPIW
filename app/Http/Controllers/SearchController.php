<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use App\Models\Movie;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $q = trim((string) $request->query('q', ''));

        $filters = [
            'genre_id' => $request->query('genre_id'),
            'release_year' => $request->query('release_year'),
            'min_score' => $request->query('min_score'),
        ];

        $movies = Movie::query()
            ->withRatingsStats()
            ->with(['genres:id,name'])
            ->search($q)
            ->filter($filters)
            ->orderByDesc('ratings_avg_score')
            ->orderByDesc('ratings_count')
            ->paginate(12)
            ->withQueryString();

        $genres = Genre::query()->orderBy('name')->get(['id', 'name']);
        $releaseYears = Movie::query()
            ->whereNotNull('release_year')
            ->select('release_year')
            ->distinct()
            ->orderByDesc('release_year')
            ->pluck('release_year');

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

    public function suggest(Request $request): JsonResponse
    {
        $q = trim((string) $request->query('q', ''));

        if (mb_strlen($q) < 2) {
            return response()->json([
                'data' => [],
                'see_more_url' => route('search', ['q' => $q]),
            ]);
        }

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
