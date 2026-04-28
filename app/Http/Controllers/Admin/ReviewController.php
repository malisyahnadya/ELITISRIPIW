<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReviewController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('search')->toString();
        $sort = $request->string('sort', 'desc')->toString();
        $sort = $sort === 'asc' ? 'asc' : 'desc';

        $reviews = Review::query()
            ->with(['user:id,name,username,email', 'movie:id,title,release_year'])
            ->withCount('likes')
            ->search($search)
            ->orderBy('created_at', $sort)
            ->paginate(15)
            ->withQueryString();

        return view('admin.reviews.index', compact('reviews', 'search', 'sort'));
    }

    public function destroy(Review $review): RedirectResponse
    {
        $review->likes()->delete();
        $review->delete();

        return redirect()->route('admin.reviews.index')
            ->with('success', 'Review berhasil dihapus dari monitoring.');
    }
}
