<x-admin-layout title="Monitoring Review" subtitle="Pantau review user, cari review bermasalah, dan hapus jika perlu.">
    <section class="admin-search-card">
        <form method="GET" action="{{ route('admin.reviews.index') }}" class="admin-filter-form">
            <input class="admin-input" name="search" value="{{ $search }}" placeholder="Search review, user, movie">
            <select class="admin-select" name="sort">
                <option value="desc" @selected($sort === 'desc')>Newest First</option>
                <option value="asc" @selected($sort === 'asc')>Oldest First</option>
            </select>
            <button class="admin-btn" type="submit"><i class="bi bi-search"></i> Search</button>
            @if($search)<a href="{{ route('admin.reviews.index') }}" class="admin-btn-outline">Reset</a>@endif
        </form>
        <span class="admin-badge">{{ $reviews->total() }} review</span>
    </section>

    <div style="height:1rem"></div>

    <section class="admin-panel">
        <header class="admin-panel-head">
            <h2>Reviews</h2>
            <span class="admin-badge">Monitoring</span>
        </header>
        <div class="admin-panel-body admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Movie</th>
                        <th>Review</th>
                        <th>Likes</th>
                        <th>Created</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reviews as $review)
                        <tr>
                            <td>
                                {{ $review->user?->name ?: $review->user?->username ?: 'Unknown' }}
                                <div class="admin-help">{{ $review->user?->email }}</div>
                            </td>
                            <td>
                                @if($review->movie)
                                    <a href="{{ route('movies.show', $review->movie) }}" style="color:#fff;text-decoration:underline">{{ $review->movie->title }}</a>
                                    <div class="admin-help">{{ $review->movie->release_year }}</div>
                                @else
                                    -
                                @endif
                            </td>
                            <td><div class="admin-review-text">{{ str($review->review_text)->limit(180) }}</div></td>
                            <td>{{ $review->likes_count ?? 0 }}</td>
                            <td>{{ optional($review->created_at)->format('Y-m-d H:i') ?? '-' }}</td>
                            <td>
                                <form method="POST" action="{{ route('admin.reviews.destroy', $review) }}" onsubmit="return confirm('Hapus review ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="admin-btn-danger" type="submit">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="admin-empty">Belum ada review untuk dimonitor.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <div class="admin-pagination">{{ $reviews->links() }}</div>
</x-admin-layout>
