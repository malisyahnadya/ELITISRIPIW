<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\ReviewReport;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ReviewReportController extends Controller
{
    public function store(Request $request, Review $review): RedirectResponse
    {
        $user = $request->user();
        $userId = (int) $user->id;
        $reviewId = (int) $review->id;

        if ((int) $review->user_id === $userId) {
            return back()->with('error', 'Tidak bisa melaporkan review sendiri.');
        }

        $validated = $request->validate([
            'reason' => 'nullable|string|max:120',
        ]);

        if (ReviewReport::existsForUserAndReview($userId, $reviewId)) {
            return back()->with('error', 'Review sudah kamu laporkan.');
        }

        ReviewReport::create([
            'user_id' => $userId,
            'review_id' => $reviewId,
            'reason' => $validated['reason'] ?? null,
        ]);

        return back()->with('success', 'Terima kasih, laporanmu sudah diterima.');
    }
}
