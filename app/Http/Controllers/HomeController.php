<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Watchlist;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        // Section "Popular": 6 film teratas berdasarkan jumlah rating lalu nilai rata-rata.
        // Field yang dipakai frontend card: id, title, release_year, duration_minutes, poster_path, banner_path.
        // Nilai rating agregat (ratings_count, ratings_avg_score) ditambahkan oleh scope withRatingsStats().
        $popularMovies = Movie::query()
            ->select([
                'id',
                'title',
                'release_year',
                'duration_minutes',
                'poster_path',
                'banner_path',
            ])
            ->withRatingsStats()
            ->orderByDesc('ratings_count')
            ->orderByDesc('ratings_avg_score')
            ->limit(6)
            ->get();

        // Section "Recommended": gabungan engagement (rating + review) untuk kartu rekomendasi.
        // Scope forHomeCard() menjaga field tetap ringan untuk rendering list/grid di homepage.
        // reviews_count dipakai untuk urutan engagement dan bisa dipakai frontend sebagai metadata tambahan.
        $recommendedMovies = Movie::query()
            ->forHomeCard()
            ->withCount('reviews')
            ->orderByRaw('(ratings_count + reviews_count) DESC')
            ->orderByDesc('ratings_avg_score')
            ->limit(12)
            ->get();

        // Default kosong untuk user yang belum login.
        // Ini memudahkan frontend: selalu menerima collection, jadi tidak perlu null-check ekstra.
        $userWatchlist = collect();

        if (Auth::check()) {
            // Section "Watchlist Saya": hanya data user login, urut update terbaru.
            // Relation movie ikut di-load agar frontend bisa render data film tanpa query tambahan (N+1).
            $userWatchlist = Watchlist::query()
                ->forUser((int) Auth::id())
                ->with([
                    'movie' => function ($query) {
                        $query->forHomeCard();
                    },
                ])
                ->latest('updated_at')
                ->limit(12)
                ->get();
        }

        // Nama key di bawah dipakai langsung oleh halaman home:
        // popularMovies => section popular, recommendedMovies => section rekomendasi, userWatchlist => section watchlist user.
        return view('home', [
            'popularMovies' => $popularMovies,
            'recommendedMovies' => $recommendedMovies,
            'userWatchlist' => $userWatchlist,
        ]);
    }
}
