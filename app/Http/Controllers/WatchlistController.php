<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Watchlist;

class WatchlistController extends Controller
{
    public function index()
    {
    $watchlist = Watchlist::query()
        ->where('user_id', auth()->id())
        ->with(['movie' => fn($q) => $q->forHomeCard()])
        ->latest('updated_at')
        ->paginate(18); // Di halaman khusus, kasih lebih banyak per page

    return view('watchlist.index', compact('watchlist'));
    }
}
