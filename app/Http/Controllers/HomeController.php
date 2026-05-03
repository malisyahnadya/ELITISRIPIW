<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Review;
use App\Models\Watchlist;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    // Menampilkan halaman utama dengan daftar film unggulan, rekomendasi, watchlist pengguna, dan ulasan terbaru
    public function index()
    {
        $currentMonth = (int) now()->month;
        $currentYear = (int) now()->year;
        $monthName = strtoupper(now()->format('F'));

        $heroMovies = Movie::query()
            ->select(['id', 'title', 'description', 'release_year', 'duration_minutes', 'poster_path', 'banner_path', 'trailer_url'])
            ->withRatingsStats()
            ->withCount([
                'ratings as month_ratings_count' => fn ($query) => $query
                    ->whereMonth('created_at', $currentMonth)
                    ->whereYear('created_at', $currentYear),
            ])
            ->withAvg([
                'ratings as month_avg_score' => fn ($query) => $query
                    ->whereMonth('created_at', $currentMonth)
                    ->whereYear('created_at', $currentYear),
            ], 'score')
            ->whereHas('ratings', fn ($query) => $query
                ->whereMonth('created_at', $currentMonth)
                ->whereYear('created_at', $currentYear))
            ->orderByDesc('month_avg_score')
            ->orderByDesc('month_ratings_count')
            ->take(5)
            ->get();

        if ($heroMovies->isEmpty()) {
            $heroMovies = Movie::query()
                ->select(['id', 'title', 'description', 'release_year', 'duration_minutes', 'poster_path', 'banner_path', 'trailer_url'])
                ->withRatingsStats()
                ->orderByDesc('ratings_avg_score')
                ->orderByDesc('ratings_count')
                ->take(5)
                ->get();
        }

        $recommendedMovies = Movie::query()
            ->forHomeCard()
            ->withCount('reviews')
            ->when(Auth::check(), function ($query) {
            $query->with([
                'watchlists' => function ($watchlistQuery) {
                $watchlistQuery
                    ->where('user_id', Auth::id())
                    ->select('id', 'user_id', 'movie_id', 'status');
                },
            ]);
            })
            ->orderByRaw('(ratings_count + reviews_count) DESC')
            ->orderByDesc('ratings_avg_score')
            ->take(12)
            ->get();

        $hasMoreRecommended = Movie::count() > 12;

        $userWatchlist = collect();
        $hasMoreWatchlist = false;

        if (Auth::check()) {
            $userWatchlist = Watchlist::query()
                ->forUser((int) Auth::id())
                ->with(['movie' => fn ($query) => $query->forHomeCard()])
                ->latest('updated_at')
                ->take(10)
                ->get();

            $hasMoreWatchlist = Watchlist::where('user_id', Auth::id())->count() > 10;
        }

        $latestReviews = Review::query()
            ->with([
                'user:id,name,username,profile_photo',
                'movie:id,title,release_year,poster_path,duration_minutes',
            ])
            ->withLikesCount()
            ->orderByDesc('likes_count')
            ->latestFirst()
            ->take(5)
            ->get();

        return view('home', compact(
            'heroMovies',
            'monthName',
            'recommendedMovies',
            'userWatchlist',
            'latestReviews',
            'hasMoreRecommended',
            'hasMoreWatchlist'
        ));
    }
}
