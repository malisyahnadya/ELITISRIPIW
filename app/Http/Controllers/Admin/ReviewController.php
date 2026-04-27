<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;

class ReviewController extends Controller
{
    /**
     * Nampilin daftar review buat dipantau admin.
     */
    public function index(): View
    {
        $search = request('search');
        $sort   = request('sort', 'desc');

        $reviews = Review::query()
            ->with(['user', 'movie']) // WAJIB: Eager loading biar gak lemot pas manggil nama user & judul film
            ->search($search)         // Asumsi lo bikin scopeSearch di Model Review
            ->latest()                // Review terbaru muncul paling atas
            ->paginate(15)
            ->withQueryString();

        return view('admin.reviews.index', compact('reviews', 'search', 'sort'));
    }

    /**
     * Hapus review yang melanggar aturan (Spam/Toxic).
     */
    public function destroy(Review $review): RedirectResponse
    {
        // Langsung eksekusi hapus tanpa ampun
        $review->delete();

        return redirect()->route('admin.reviews.index')
            ->with('success', 'Review has been successfully moderated (deleted).');
    }
}
