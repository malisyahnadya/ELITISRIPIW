<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Watchlist;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WatchlistController extends Controller
{
    public function index(): View
    {
        $watchlist = Watchlist::query()
            ->where('user_id', auth()->id())
            ->with(['movie' => fn ($query) => $query->forHomeCard()])
            ->latest('updated_at')
            ->paginate(18);

        return view('watchlist.index', compact('watchlist'));
    }

    public function store(Request $request, Movie $movie): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['nullable', 'in:plan_to_watch,watching,completed'],
        ]);

        Watchlist::updateOrCreate(
            [
                'user_id' => (int) $request->user()->id,
                'movie_id' => (int) $movie->id,
            ],
            [
                'status' => $validated['status'] ?? Watchlist::STATUS_PLAN_TO_WATCH,
            ]
        );

        return back()->with('success', 'Watchlist berhasil diperbarui.');
    }
}
