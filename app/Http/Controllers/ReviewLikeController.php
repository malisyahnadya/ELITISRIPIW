<?php

namespace App\Http\Controllers;

// Model review yang akan di-like/unlike.
use App\Models\Review;
use App\Models\ReviewLike;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ReviewLikeController extends Controller
{
    // Toggle like: jika sudah like maka hapus, jika belum maka buat.
    public function toggle(Request $request, Review $review): RedirectResponse
    {
        // Ambil ID user yang sedang login sebagai integer.
        $userId = (int) $request->user()->id;

        // Cari apakah user ini sudah pernah like review yang dimaksud.
        $existingLike = ReviewLike::query()
            // Filter berdasarkan user yang login.
            ->forUser($userId)
            // Filter berdasarkan review target.
            ->forReview((int) $review->id)
            // Ambil satu data pertama (jika ada).
            ->first();

        // Jika like sudah ada, lakukan unlike (hapus data like).
        if ($existingLike) {
            // Hapus record like yang ditemukan.
            $existingLike->delete();

            // Kembali ke halaman sebelumnya dengan flash message sukses.
            return back()->with('success', 'Like review dibatalkan.');
        }

        // Jika belum ada like, buat record like baru.
        ReviewLike::create([
            // Simpan ID user yang melakukan like.
            'user_id' => $userId,
            // Simpan ID review yang di-like.
            'review_id' => (int) $review->id,
        ]);

        // Kembali ke halaman sebelumnya dengan pesan sukses.
        return back()->with('success', 'Review berhasil disukai.');
    }
}
