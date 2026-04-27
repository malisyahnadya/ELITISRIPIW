<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use App\Models\Review;
use Illuminate\Http\Request;
use App\Models\Movie;

class MovieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
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

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Movie $movie)
    {
        $movie->load([
            'directors:id,name',
            'actors:id,name',
            'genres:id,name',
            'reviews' => function ($query) {
                $query->with(['user:id,username'])
                    ->latestFirst();
            },
        ]);

        $movie->loadCount('ratings');
        $movie->loadAvg('ratings', 'score');

        $userRating = null;
        $userReview = null;

        if (auth()->check()) {
            $userId = (int) auth()->id();

            $userRating = Rating::query()
                ->forMovie((int) $movie->id)
                ->forUser($userId)
                ->first();

            $userReview = Review::query()
                ->forMovie((int) $movie->id)
                ->forUser($userId)
                ->first();
        }

        return view('movies.movie', [
            'movie' => $movie,
            'userRating' => $userRating,
            'userReview' => $userReview,
        ]);
    }

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

        return back()->with('status', 'rating-saved');
    }

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

        return back()->with('status', 'review-saved');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}