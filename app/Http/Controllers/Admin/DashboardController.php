<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Actor;
use App\Models\Director;
use App\Models\Genre;
use App\Models\Movie;
use App\Models\Rating;
use App\Models\Review;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $movieCount = Movie::count();
        $userCount = User::count();
        $reviewCount = Review::count();
        $ratingCount = Rating::count();

        $stats = [
            'movies' => $movieCount,
            'users' => $userCount,
            'reviews' => $reviewCount,
            'ratings' => $ratingCount,
            'actors' => Actor::count(),
            'genres' => Genre::count(),
            'directors' => Director::count(),
            'avg_rating' => Rating::avg('score') ?? 0,
            'watchlists' => DB::table('watchlists')->count(),
            'admin_users' => User::where('role', 'admin')->count(),
            'avg_reviews_per_user' => $userCount > 0 ? $reviewCount / $userCount : 0,
        ];

        $latestMovies = Movie::query()
            ->with(['genres:id,name'])
            ->withRatingsStats()
            ->latest()
            ->take(6)
            ->get();

        $genreLeaders = Genre::query()
            ->withCount('movies')
            ->orderByDesc('movies_count')
            ->orderBy('name')
            ->take(5)
            ->get();

        $actorLeaders = Actor::query()
            ->withCount('movies')
            ->orderByDesc('movies_count')
            ->orderBy('name')
            ->take(5)
            ->get();

        $directorLeaders = Director::query()
            ->withCount('movies')
            ->orderByDesc('movies_count')
            ->orderBy('name')
            ->take(5)
            ->get();

        $users = User::query()
            ->withCount(['reviews', 'ratings'])
            ->latest()
            ->take(8)
            ->get();

        $reviews = Review::query()
            ->with(['user:id,name,username', 'movie:id,title'])
            ->withCount('likes')
            ->latest()
            ->take(5)
            ->get();

        // Movie dengan rating tertinggi
        $topRatedMovies = DB::table('movies')
            ->leftJoin('ratings', 'movies.id', '=', 'ratings.movie_id')
            ->select(
                'movies.id',
                'movies.title',
                'movies.release_year',
                DB::raw('COALESCE(AVG(ratings.score), 0) as avg_rating'),
                DB::raw('COUNT(ratings.id) as ratings_count')
            )
            ->groupBy('movies.id', 'movies.title', 'movies.release_year')
            ->orderByDesc('avg_rating')
            ->orderByDesc('ratings_count')
            ->take(5)
            ->get();

        // Movie dengan review terbanyak
        $mostReviewedMovies = DB::table('movies')
            ->leftJoin('reviews', 'movies.id', '=', 'reviews.movie_id')
            ->select(
                'movies.id',
                'movies.title',
                DB::raw('COUNT(reviews.id) as reviews_count')
            )
            ->groupBy('movies.id', 'movies.title')
            ->orderByDesc('reviews_count')
            ->orderBy('movies.title')
            ->take(5)
            ->get();

        // Movie paling banyak masuk watchlist
        $mostWatchlistedMovies = DB::table('movies')
            ->leftJoin('watchlists', 'movies.id', '=', 'watchlists.movie_id')
            ->select(
                'movies.id',
                'movies.title',
                DB::raw('COUNT(watchlists.id) as watchlists_count')
            )
            ->groupBy('movies.id', 'movies.title')
            ->orderByDesc('watchlists_count')
            ->orderBy('movies.title')
            ->take(5)
            ->get();

        // Review terbaru
        $latestReviews = Review::query()
            ->with(['user:id,name,username', 'movie:id,title'])
            ->latest()
            ->take(5)
            ->get();

        // Review per bulan untuk mini chart
        $reviewActivity = Review::query()
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month")
            ->selectRaw('COUNT(*) as total')
            ->whereNotNull('created_at')
            ->where('created_at', '>=', now()->subMonths(5)->startOfMonth())
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Distribusi rating 1-5
        $ratingDistribution = Rating::query()
            ->select('score')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('score')
            ->orderByDesc('score')
            ->get();

        // Trending movies berdasarkan total review + rating + watchlist
        $trendingMovies = Movie::query()
            ->select('movies.id', 'movies.title', 'movies.release_year')
            ->selectSub(function ($query) {
                $query->from('reviews')
                    ->selectRaw('COUNT(*)')
                    ->whereColumn('reviews.movie_id', 'movies.id');
            }, 'reviews_count')
            ->selectSub(function ($query) {
                $query->from('ratings')
                    ->selectRaw('COUNT(*)')
                    ->whereColumn('ratings.movie_id', 'movies.id');
            }, 'ratings_count')
            ->selectSub(function ($query) {
                $query->from('watchlists')
                    ->selectRaw('COUNT(*)')
                    ->whereColumn('watchlists.movie_id', 'movies.id');
            }, 'watchlists_count')
            ->get()
            ->map(function ($movie) {
                $movie->trend_score =
                    ((int) $movie->reviews_count * 3) +
                    ((int) $movie->ratings_count * 2) +
                    ((int) $movie->watchlists_count);

                return $movie;
            })
            ->sortByDesc('trend_score')
            ->take(5)
            ->values();

        // Import TMDB terbaru
        $recentTmdbImports = Movie::query()
            ->whereNotNull('tmdb_id')
            ->latest()
            ->take(5)
            ->get(['id', 'title', 'release_year', 'tmdb_id', 'created_at']);

        // Status kelengkapan data movie
        $movieCompleteness = [
            'missing_poster' => Movie::whereNull('poster_path')->orWhere('poster_path', '')->count(),
            'missing_banner' => Movie::whereNull('banner_path')->orWhere('banner_path', '')->count(),
            'missing_trailer' => Movie::whereNull('trailer_url')->orWhere('trailer_url', '')->count(),
            'without_actors' => Movie::doesntHave('actors')->count(),
            'without_directors' => Movie::doesntHave('directors')->count(),
            'without_genres' => Movie::doesntHave('genres')->count(),
        ];

        // User paling aktif
        $activeUsers = User::query()
            ->withCount(['reviews', 'ratings'])
            ->get()
            ->map(function ($user) {
                $user->activity_score =
                    ((int) $user->reviews_count * 3) +
                    ((int) $user->ratings_count * 2);

                return $user;
            })
            ->sortByDesc('activity_score')
            ->take(5)
            ->values();

        // Alert moderasi review
        $reviewAlerts = Review::query()
            ->with(['user:id,name,username', 'movie:id,title'])
            ->withCount('likes')
            ->whereRaw('CHAR_LENGTH(review_text) >= 300')
            ->latest()
            ->take(5)
            ->get();

        // Checklist kualitas sistem
        $dataQuality = [
            'tmdb_api_connected' => filled(config('services.tmdb.key') ?: env('TMDB_API_KEY')),
            'storage_linked' => file_exists(public_path('storage')),
            'movies_total' => $movieCount,
            'movies_with_poster' => Movie::whereNotNull('poster_path')->where('poster_path', '!=', '')->count(),
            'movies_with_trailer' => Movie::whereNotNull('trailer_url')->where('trailer_url', '!=', '')->count(),
        ];

        return view('admin.index', compact(
            'stats',
            'latestMovies',
            'genreLeaders',
            'actorLeaders',
            'directorLeaders',
            'users',
            'reviews',
            'topRatedMovies',
            'mostReviewedMovies',
            'mostWatchlistedMovies',
            'latestReviews',
            'reviewActivity',
            'ratingDistribution',
            'trendingMovies',
            'recentTmdbImports',
            'movieCompleteness',
            'activeUsers',
            'reviewAlerts',
            'dataQuality'
        ));
    }
}