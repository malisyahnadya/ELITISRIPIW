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
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'movies' => Movie::count(),
            'users' => User::count(),
            'reviews' => Review::count(),
            'ratings' => Rating::count(),
            'actors' => Actor::count(),
            'genres' => Genre::count(),
            'directors' => Director::count(),
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

        return view('admin.index', compact(
            'stats',
            'latestMovies',
            'genreLeaders',
            'actorLeaders',
            'directorLeaders',
            'users',
            'reviews'
        ));
    }
}
