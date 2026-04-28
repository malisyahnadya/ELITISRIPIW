<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Review;
use App\Models\Watchlist;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $popularMovies = Movie::query()
            ->select(['id', 'title', 'description', 'release_year', 'duration_minutes', 'poster_path', 'banner_path'])
            ->withRatingsStats()
            ->orderByDesc('ratings_count')
            ->orderByDesc('ratings_avg_score')
            ->limit(6)
            ->get();

        $featuredReview = Review::query()
            ->with([
                'movie' => fn ($query) => $query
                    ->select(['id', 'title', 'description', 'release_year', 'duration_minutes', 'poster_path', 'banner_path'])
                    ->withRatingsStats(),
                'user:id,name,username,profile_photo',
            ])
            ->withLikesCount()
            ->orderByDesc('likes_count')
            ->latestFirst()
            ->first();

        $recommendedMovies = Movie::query()
            ->forHomeCard()
            ->withCount('reviews')
            ->orderByRaw('(ratings_count + reviews_count) DESC')
            ->orderByDesc('ratings_avg_score')
            ->take(10)
            ->get();

        $hasMoreRecommended = Movie::count() > 10;

        $userWatchlist = collect();
        $hasMoreWatchlist = false;

        if (Auth::check()) {
            $userWatchlist = Watchlist::query()
                ->forUser((int) Auth::id())
                ->with(['movie' => fn ($query) => $query->forHomeCard()])
                ->latest('updated_at')
                ->take(3)
                ->get();

            $hasMoreWatchlist = Watchlist::where('user_id', Auth::id())->count() > 3;
        }

        $latestReviews = Review::query()
            ->with([
                'user:id,name,username,profile_photo',
                'movie:id,title,release_year,poster_path,duration_minutes',
            ])
            ->withLikesCount()
            ->latestFirst()
            ->take(6)
            ->get();

        return view('home', compact(
            'popularMovies',
            'featuredReview',
            'recommendedMovies',
            'userWatchlist',
            'latestReviews',
            'hasMoreRecommended',
            'hasMoreWatchlist'
        ));
    }
}
