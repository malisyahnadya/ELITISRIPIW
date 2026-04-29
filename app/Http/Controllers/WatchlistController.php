<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Watchlist;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WatchlistController extends Controller
{
    // Metode untuk menampilkan daftar film di watchlist pengguna.
    public function index(): View
    {
        // Mengambil daftar film di watchlist pengguna saat ini dengan status terbaru, memuat data film untuk tampilan kartu, dan melakukan paginasi.
        $watchlist = Watchlist::query()
            ->where('user_id', auth()->id())
            ->with(['movie' => fn ($query) => $query->forHomeCard()])
            ->latest('updated_at')
            ->paginate(18);

        return view('watchlist.index', compact('watchlist'));
    }

    // Metode untuk menambahkan atau memperbarui status film di watchlist pengguna.
    public function store(Request $request, Movie $movie): RedirectResponse
    {
        // Validasi input status watchlist yang diterima dari request, memastikan nilainya sesuai dengan opsi yang diizinkan (plan_to_watch, watching, completed).
        $validated = $request->validate([
            'status' => ['nullable', 'in:plan_to_watch,watching,completed'],
        ]);

        // Menggunakan metode updateOrCreate untuk menambahkan film ke watchlist pengguna jika belum ada, atau memperbarui statusnya jika sudah ada. Kunci uniknya adalah kombinasi user_id dan movie_id, sehingga setiap pengguna hanya bisa memiliki satu entri untuk setiap film di watchlist mereka.
        Watchlist::updateOrCreate(
            [
                'user_id' => (int) $request->user()->id,
                'movie_id' => (int) $movie->id,
            ],
            [
                'status' => $validated['status'] ?? Watchlist::STATUS_PLAN_TO_WATCH,
            ]
        );

        // Mengembalikan respons redirect kembali ke halaman sebelumnya dengan pesan sukses bahwa watchlist berhasil diperbarui.
        return back()->with('success', 'Watchlist berhasil diperbarui.');
    }
}
