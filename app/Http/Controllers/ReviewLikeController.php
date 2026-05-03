<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\ReviewLike;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ReviewLikeController extends Controller
{
    public function toggle(Request $request, Review $review): RedirectResponse
    {
        $userId = (int) $request->user()->id;

        $existingLike = ReviewLike::query()
            ->forUser($userId)
            ->forReview((int) $review->id)
            ->first();

        if ($existingLike) {
            $existingLike->delete();

            return back()->with('success', 'Like review dibatalkan.');
        }

        ReviewLike::create([
            'user_id' => $userId,
            'review_id' => (int) $review->id,
        ]);

        return back()->with('success', 'Review berhasil disukai.');
    }
}
