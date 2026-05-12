<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReviewReport;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReviewReportController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('search')->toString();
        $sort = $request->string('sort', 'desc')->toString();
        $sort = $sort === 'asc' ? 'asc' : 'desc';

        $reports = ReviewReport::query()
            ->with([
                'user:id,name,username,email',
                'review.user:id,name,username,email',
                'review.movie:id,title,release_year',
            ])
            ->search($search)
            ->orderBy('created_at', $sort)
            ->paginate(10)
            ->withQueryString();

        return view('admin.review-reports.index', compact('reports', 'search', 'sort'));
    }

    public function destroy(ReviewReport $reviewReport): RedirectResponse
    {
        $reviewReport->delete();

        return redirect()->route('admin.review-reports.index')
            ->with('success', 'Laporan review berhasil dihapus.');
    }
}
