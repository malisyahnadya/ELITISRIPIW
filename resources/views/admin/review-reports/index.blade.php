<x-admin-layout title="Review Reports" subtitle="Lihat laporan review dari user dan tindak lanjuti bila perlu.">
    <section class="admin-search-card">
        <form method="GET" action="{{ route('admin.review-reports.index') }}" class="admin-filter-form">
            <input class="admin-input" name="search" value="{{ $search }}" placeholder="Search report, user, movie">
            <select class="admin-select" name="sort">
                <option value="desc" @selected($sort === 'desc')>Newest First</option>
                <option value="asc" @selected($sort === 'asc')>Oldest First</option>
            </select>
            <button class="admin-btn" type="submit"><i class="bi bi-search"></i> Search</button>
            @if($search)<a href="{{ route('admin.review-reports.index') }}" class="admin-btn-outline">Reset</a>@endif
        </form>
        <span class="admin-badge">{{ $reports->total() }} report</span>
    </section>

    <div style="height:1rem"></div>

    <section class="admin-panel">
        <header class="admin-panel-head">
            <h2>Reported Reviews</h2>
            <span class="admin-badge">Monitoring</span>
        </header>
        <div class="admin-panel-body admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Reporter</th>
                        <th>Review Author</th>
                        <th>Movie</th>
                        <th>Review</th>
                        <th>Reported</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reports as $report)
                        <tr>
                            <td>
                                {{ $report->user?->name ?: $report->user?->username ?: 'Unknown' }}
                                <div class="admin-help">{{ $report->user?->email }}</div>
                            </td>
                            <td>
                                {{ $report->review?->user?->name ?: $report->review?->user?->username ?: 'Unknown' }}
                                <div class="admin-help">{{ $report->review?->user?->email }}</div>
                            </td>
                            <td>
                                @if($report->review?->movie)
                                    <a href="{{ route('movies.show', $report->review->movie) }}" style="color:#fff;text-decoration:underline">
                                        {{ $report->review->movie->title }}
                                    </a>
                                    <div class="admin-help">{{ $report->review->movie->release_year }}</div>
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                <div class="admin-review-text">
                                    {{ str($report->review?->review_text)->limit(180) }}
                                </div>
                            </td>
                            <td>{{ optional($report->created_at)->format('Y-m-d H:i') ?? '-' }}</td>
                            <td>
                                <div class="admin-actions">
                                    <form method="POST" action="{{ route('admin.review-reports.destroy', $report) }}" onsubmit="return confirm('Hapus laporan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="admin-btn-soft" type="submit">Dismiss</button>
                                    </form>

                                    @if($report->review)
                                        <form method="POST" action="{{ route('admin.reviews.destroy', $report->review) }}" onsubmit="return confirm('Hapus review ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="admin-btn-danger" type="submit">Delete Review</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="admin-empty">Belum ada laporan review dari user.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <div class="admin-pagination">{{ $reports->links() }}</div>
</x-admin-layout>
