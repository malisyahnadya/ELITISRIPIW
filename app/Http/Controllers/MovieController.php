<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Rating;
use App\Models\Review;
use App\Models\Watchlist;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    
    public function index()
    {
        // Ambil data minimal untuk card beranda + statistik rating agar tidak memicu query tambahan di view.
        $movies = Movie::query()
            ->forHomeCard()
            ->orderByDesc('release_year')
            ->orderBy('title')
            ->paginate(12);

        return view('search', [
            'movies' => $movies,
            'genres' => collect(),
            'releaseYears' => collect(),
            'q' => '',
            'selectedGenreId' => null,
            'selectedReleaseYear' => null,
            'selectedMinScore' => null,
        ]);
    }

    public function show(Movie $movie)
    {
        // Eager load relasi utama untuk halaman detail film.
        $movie->load([
            'directors:id,name,photo_path',
            'actors:id,name,photo_path',
            'genres:id,name',
            'reviews' => function ($query) {
                $query->with(['user:id,name,username,profile_photo'])
                    ->withLikesCount()
                    ->latestFirst();
            },
        ]);

        // Preload agregat rating agar accessor average_score memakai nilai yang sudah tersedia.
        $movie->loadCount('ratings');
        $movie->loadAvg('ratings', 'score');

        $userRating = null;
        $userReview = null;
        $userWatchlist = null;

        if (auth()->check()) {
            $userId = (int) auth()->id();

            // Ambil interaksi user saat ini terhadap film (rating/review/watchlist) bila sudah login.
            $userRating = Rating::query()
                ->forMovie((int) $movie->id)
                ->forUser($userId)
                ->first();

            $userReview = Review::query()
                ->forMovie((int) $movie->id)
                ->forUser($userId)
                ->first();

            $userWatchlist = Watchlist::query()
                ->forMovie((int) $movie->id)
                ->forUser($userId)
                ->first();
        }

        return view('movies.movie', compact('movie', 'userRating', 'userReview', 'userWatchlist'));
    }

    // Metode untuk menyimpan atau memperbarui rating user terhadap film tertentu.
    public function storeRating(Request $request, Movie $movie)
    {
        $validated = $request->validate([
            'score' => ['required', 'integer', 'between:' . Rating::MIN_SCORE . ',' . Rating::MAX_SCORE],
        ]);

        Rating::updateOrCreate(
            [
                'user_id' => (int) $request->user()->id,
                'movie_id' => (int) $movie->id,
            ],
            ['score' => (int) $validated['score']]
        );

        return back()->with('success', 'Rating berhasil disimpan.');
    }

    // Metode untuk menyimpan atau memperbarui review user terhadap film tertentu.
    public function storeReview(Request $request, Movie $movie)
    {
        $validated = $request->validate([
            'review_text' => ['required', 'string', 'min:3', 'max:3000'],
        ]);

        Review::updateOrCreate(
            [
                'user_id' => (int) $request->user()->id,
                'movie_id' => (int) $movie->id,
            ],
            ['review_text' => $validated['review_text']]
        );

        return back()->with('success', 'Review berhasil disimpan.');
    }
}
