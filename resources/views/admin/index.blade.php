@php
    $stats = $stats ?? ['movies' => 0, 'users' => 0, 'reviews' => 0, 'ratings' => 0];
    $latestMovies = $latestMovies ?? collect();
    $genreLeaders = $genreLeaders ?? collect();
    $actorLeaders = $actorLeaders ?? collect();
    $directorLeaders = $directorLeaders ?? collect();
    $users = $users ?? collect();
    $topRatedMovies = $topRatedMovies ?? collect();
    $mostReviewedMovies = $mostReviewedMovies ?? collect();
    $mostWatchlistedMovies = $mostWatchlistedMovies ?? collect();
    $latestReviews = $latestReviews ?? collect();

    $reviewActivity = $reviewActivity ?? collect();
    $ratingDistribution = $ratingDistribution ?? collect();
    $trendingMovies = $trendingMovies ?? collect();
    $recentTmdbImports = $recentTmdbImports ?? collect();
    $movieCompleteness = $movieCompleteness ?? [];
    $activeUsers = $activeUsers ?? collect();
    $reviewAlerts = $reviewAlerts ?? collect();
    $dataQuality = $dataQuality ?? [];
@endphp

<x-admin-layout title="Control Room" eyebrow="Dashboard">
    <section class="admin-stats" aria-label="Admin stats">
        <article class="admin-stat">
            <span>Movies</span>
            <strong>{{ $stats['movies'] ?? 0 }}</strong>
            <small>total catalog film</small>
        </article>

        <article class="admin-stat">
            <span>Users</span>
            <strong>{{ $stats['users'] ?? 0 }}</strong>
            <small>akun terdaftar</small>
        </article>

        <article class="admin-stat">
            <span>Reviews</span>
            <strong>{{ $stats['reviews'] ?? 0 }}</strong>
            <small>review aktif</small>
        </article>

        <article class="admin-stat">
            <span>Ratings</span>
            <strong>{{ $stats['ratings'] ?? 0 }}</strong>
            <small>penilaian tersimpan</small>
        </article>

        <article class="admin-stat">
            <span>Avg Rating</span>
            <strong>{{ number_format((float) ($stats['avg_rating'] ?? 0), 2) }}</strong>
            <small>out of 5</small>
        </article>

        <article class="admin-stat">
            <span>Watchlist Items</span>
            <strong>{{ $stats['watchlists'] ?? 0 }}</strong>
            <small>semua user</small>
        </article>

        <article class="admin-stat">
            <span>Admin Users</span>
            <strong>{{ $stats['admin_users'] ?? 0 }}</strong>
            <small>moderator aktif</small>
        </article>

        <article class="admin-stat">
            <span>Avg Reviews/User</span>
            <strong>{{ number_format((float) ($stats['avg_reviews_per_user'] ?? 0), 2) }}</strong>
            <small>engagement</small>
        </article>
    </section>

    <div style="height:1.55rem"></div>

    <section class="admin-dashboard-grid">
        <div class="admin-panel">
            <header class="admin-panel-head">
                <h2>Recent Movies</h2>
                <a href="{{ route('admin.movies.create') }}" class="admin-btn-outline">+ Add Movie</a>
            </header>

            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Film</th>
                            <th>Year</th>
                            <th>Duration</th>
                            <th>Rating</th>
                            <th>Added</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($latestMovies as $movie)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.movies.edit', $movie) }}">
                                        {{ $movie->title }}
                                    </a>
                                </td>
                                <td>{{ $movie->release_year ?? '-' }}</td>
                                <td>{{ $movie->duration_formatted }}</td>
                                <td>
                                    {{ $movie->ratings_avg_score ? number_format((float) $movie->ratings_avg_score, 1) : '-' }}
                                    ({{ $movie->ratings_count ?? 0 }})
                                </td>
                                <td>{{ optional($movie->created_at)->format('Y-m-d') ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="admin-empty">
                                    Belum ada movie. Klik Add Movie untuk menambahkan data pertama.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="admin-panel">
            <header class="admin-panel-head">
                <h2>Recent Users</h2>
                <a href="{{ route('admin.users.index') }}" class="admin-btn-outline">+ Add User</a>
            </header>

            <div class="admin-panel-body">
                @if($users->count())
                    <div class="admin-list">
                        @foreach($users as $user)
                            <a href="{{ route('admin.users.edit', $user) }}" class="admin-list-row">
                                <strong>{{ $user->name ?: $user->username }}</strong>
                                <span>{{ $user->role }}</span>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="admin-empty">Belum ada data user.</div>
                @endif
            </div>
        </div>
    </section>

    <div style="height:1.55rem"></div>

    <section class="admin-dashboard-cards">
        <div class="admin-panel">
            <header class="admin-panel-head">
                <h2>Genres</h2>
                <a href="{{ route('admin.genres.create') }}" class="admin-btn-outline">+ Add Genre</a>
            </header>

            <div class="admin-panel-body">
                <div class="admin-list">
                    @forelse($genreLeaders as $genre)
                        <a href="{{ route('admin.genres.edit', $genre) }}" class="admin-list-row">
                            <strong>{{ $genre->name }}</strong>
                            <span>{{ $genre->movies_count ?? 0 }} movies</span>
                        </a>
                    @empty
                        <div class="admin-empty">Belum ada genre.</div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="admin-panel">
            <header class="admin-panel-head">
                <h2>Actors</h2>
                <a href="{{ route('admin.actors.create') }}" class="admin-btn-outline">+ Add Actor</a>
            </header>

            <div class="admin-panel-body">
                <div class="admin-list">
                    @forelse($actorLeaders as $actor)
                        <a href="{{ route('admin.actors.edit', $actor) }}" class="admin-list-row">
                            <strong>{{ $actor->name }}</strong>
                            <span>{{ $actor->movies_count ?? 0 }} movies</span>
                        </a>
                    @empty
                        <div class="admin-empty">Belum ada actor.</div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="admin-panel">
            <header class="admin-panel-head">
                <h2>Directors</h2>
                <a href="{{ route('admin.directors.create') }}" class="admin-btn-outline">+ Add Director</a>
            </header>

            <div class="admin-panel-body">
                <div class="admin-list">
                    @forelse($directorLeaders as $director)
                        <a href="{{ route('admin.directors.edit', $director) }}" class="admin-list-row">
                            <strong>{{ $director->name }}</strong>
                            <span>{{ $director->movies_count ?? 0 }} movies</span>
                        </a>
                    @empty
                        <div class="admin-empty">Belum ada director.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </section>

    <div style="height:1.55rem"></div>

    <section class="admin-dashboard-cards">
        <div class="admin-panel">
            <header class="admin-panel-head">
                <h2>Top Rated Movies</h2>
                <a href="{{ route('admin.movies.index') }}" class="admin-btn-outline">View All</a>
            </header>

            <div class="admin-panel-body">
                <div class="admin-list">
                    @forelse($topRatedMovies as $movie)
                        <a href="{{ route('admin.movies.edit', $movie->id) }}" class="admin-list-row">
                            <strong>{{ $movie->title }}</strong>
                            <span>
                                {{ number_format((float) $movie->avg_rating, 1) }}/5 ·
                                {{ $movie->ratings_count }} ratings
                            </span>
                        </a>
                    @empty
                        <div class="admin-empty">Belum ada rating.</div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="admin-panel">
            <header class="admin-panel-head">
                <h2>Most Reviewed</h2>
                <a href="{{ route('admin.reviews.index') }}" class="admin-btn-outline">Review List</a>
            </header>

            <div class="admin-panel-body">
                <div class="admin-list">
                    @forelse($mostReviewedMovies as $movie)
                        <a href="{{ route('admin.movies.edit', $movie->id) }}" class="admin-list-row">
                            <strong>{{ $movie->title }}</strong>
                            <span>{{ $movie->reviews_count }} reviews</span>
                        </a>
                    @empty
                        <div class="admin-empty">Belum ada review.</div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="admin-panel">
            <header class="admin-panel-head">
                <h2>Most Watchlisted</h2>
                <a href="{{ route('admin.movies.index') }}" class="admin-btn-outline">Movies</a>
            </header>

            <div class="admin-panel-body">
                <div class="admin-list">
                    @forelse($mostWatchlistedMovies as $movie)
                        <a href="{{ route('admin.movies.edit', $movie->id) }}" class="admin-list-row">
                            <strong>{{ $movie->title }}</strong>
                            <span>{{ $movie->watchlists_count }} users</span>
                        </a>
                    @empty
                        <div class="admin-empty">Belum ada watchlist.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </section>

    <div style="height:1.55rem"></div>

    <section class="admin-dashboard-cards">
        <div class="admin-panel">
            <header class="admin-panel-head">
                <h2>Review Activity</h2>
            </header>

            <div class="admin-panel">
    <header class="admin-panel-head">
        <h2>Review Activity</h2>
    </header>

    <div class="admin-panel-body">
        @php
            $maxReviewActivity = max(1, (int) $reviewActivity->max('total'));
        @endphp

        <div class="admin-list">
            @forelse($reviewActivity as $item)
                @php
                    $reviewPercent = ((int) $item->total / $maxReviewActivity) * 100;
                @endphp

                <div class="admin-list-row">
                    <strong>{{ $item->month }}</strong>
                    <span>{{ $item->total }} reviews</span>
                </div>

                <div style="height:.55rem;background:rgba(255,255,255,.07);border-radius:999px;overflow:hidden;margin-bottom:.85rem">
                    <div
                        @style([
                            'height: 100%',
                            'width: ' . $reviewPercent . '%',
                            'background: #c4b5fd',
                            'border-radius: 999px',
                        ])
                    ></div>
                </div>
            @empty
                <div class="admin-empty">Belum ada aktivitas review.</div>
            @endforelse
        </div>
    </div>
</div>

        <div class="admin-panel">
            <header class="admin-panel-head">
                <h2>Data Quality</h2>
            </header>

            <div class="admin-panel-body">
                <div class="admin-list">
                    <div class="admin-list-row">
                        <strong>TMDB API</strong>
                        <span>{{ ($dataQuality['tmdb_api_connected'] ?? false) ? 'Connected' : 'Missing Key' }}</span>
                    </div>

                    <div class="admin-list-row">
                        <strong>Storage Link</strong>
                        <span>{{ ($dataQuality['storage_linked'] ?? false) ? 'Ready' : 'Not Linked' }}</span>
                    </div>

                    <div class="admin-list-row">
                        <strong>Movies With Poster</strong>
                        <span>{{ $dataQuality['movies_with_poster'] ?? 0 }}/{{ $dataQuality['movies_total'] ?? 0 }}</span>
                    </div>

                    <div class="admin-list-row">
                        <strong>Movies With Trailer</strong>
                        <span>{{ $dataQuality['movies_with_trailer'] ?? 0 }}/{{ $dataQuality['movies_total'] ?? 0 }}</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div style="height:1.55rem"></div>

    <section class="admin-dashboard-cards">
        <div class="admin-panel">
            <header class="admin-panel-head">
                <h2>Trending Movies</h2>
                <a href="{{ route('admin.movies.index') }}" class="admin-btn-outline">Movies</a>
            </header>

            <div class="admin-panel-body">
                <div class="admin-list">
                    @forelse($trendingMovies as $movie)
                        <a href="{{ route('admin.movies.edit', $movie->id) }}" class="admin-list-row">
                            <strong>{{ $movie->title }}</strong>
                            <span>score {{ $movie->trend_score }}</span>
                        </a>
                    @empty
                        <div class="admin-empty">Belum ada trending movie.</div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="admin-panel">
            <header class="admin-panel-head">
                <h2>Recent TMDB Imports</h2>
                <a href="{{ route('admin.tmdb-import.index') }}" class="admin-btn-outline">Import</a>
            </header>

            <div class="admin-panel-body">
                <div class="admin-list">
                    @forelse($recentTmdbImports as $movie)
                        <a href="{{ route('admin.movies.edit', $movie) }}" class="admin-list-row">
                            <strong>{{ $movie->title }}</strong>
                            <span>TMDB #{{ $movie->tmdb_id }}</span>
                        </a>
                    @empty
                        <div class="admin-empty">Belum ada import TMDB.</div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="admin-panel">
            <header class="admin-panel-head">
                <h2>Most Active Users</h2>
                <a href="{{ route('admin.users.index') }}" class="admin-btn-outline">Users</a>
            </header>

            <div class="admin-panel-body">
                <div class="admin-list">
                    @forelse($activeUsers as $user)
                        <a href="{{ route('admin.users.edit', $user) }}" class="admin-list-row">
                            <strong>{{ $user->name ?: $user->username }}</strong>
                            <span>
                                {{ $user->reviews_count }} reviews ·
                                {{ $user->ratings_count }} ratings
                            </span>
                        </a>
                    @empty
                        <div class="admin-empty">Belum ada aktivitas user.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </section>

    <div style="height:1.55rem"></div>

    <section class="admin-dashboard-grid">
        <div class="admin-panel">
            <header class="admin-panel-head">
                <h2>Movie Completeness</h2>
                <a href="{{ route('admin.tmdb-import.index') }}" class="admin-btn-outline">Fix with TMDB</a>
            </header>

            <div class="admin-panel-body">
                <div class="admin-list">
                    <div class="admin-list-row">
                        <strong>Missing Poster</strong>
                        <span>{{ $movieCompleteness['missing_poster'] ?? 0 }} movies</span>
                    </div>

                    <div class="admin-list-row">
                        <strong>Missing Banner</strong>
                        <span>{{ $movieCompleteness['missing_banner'] ?? 0 }} movies</span>
                    </div>

                    <div class="admin-list-row">
                        <strong>Missing Trailer</strong>
                        <span>{{ $movieCompleteness['missing_trailer'] ?? 0 }} movies</span>
                    </div>

                    <div class="admin-list-row">
                        <strong>Without Actors</strong>
                        <span>{{ $movieCompleteness['without_actors'] ?? 0 }} movies</span>
                    </div>

                    <div class="admin-list-row">
                        <strong>Without Directors</strong>
                        <span>{{ $movieCompleteness['without_directors'] ?? 0 }} movies</span>
                    </div>

                    <div class="admin-list-row">
                        <strong>Without Genres</strong>
                        <span>{{ $movieCompleteness['without_genres'] ?? 0 }} movies</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="admin-panel">
            <header class="admin-panel-head">
                <h2>Quick TMDB Import</h2>
            </header>

            <div class="admin-panel-body">
                <form method="POST" action="{{ route('admin.tmdb-import.store') }}" style="display:grid;gap:1rem">
                    @csrf

                    <input
                        type="text"
                        name="query"
                        placeholder="Movie title or TMDB ID"
                        style="width:100%;border:1px solid rgba(196,181,253,.22);background:#1b1230;color:white;border-radius:1rem;padding:.9rem 1rem;outline:none"
                        required
                    >

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:.75rem">
                        <select
                            name="import_type"
                            style="width:100%;border:1px solid rgba(196,181,253,.22);background:#1b1230;color:white;border-radius:1rem;padding:.9rem 1rem;outline:none"
                        >
                            <option value="title">Title</option>
                            <option value="id">TMDB ID</option>
                        </select>

                        <input
                            type="number"
                            name="cast_limit"
                            value="12"
                            min="1"
                            max="50"
                            style="width:100%;border:1px solid rgba(196,181,253,.22);background:#1b1230;color:white;border-radius:1rem;padding:.9rem 1rem;outline:none"
                        >
                    </div>

                    <label style="display:flex;align-items:center;gap:.65rem;color:rgba(237,233,254,.82);font-size:.85rem;font-weight:800">
                        <input type="checkbox" name="overwrite" value="1">
                        Overwrite existing data
                    </label>

                    <button type="submit" class="admin-btn-outline">
                        Import Movie
                    </button>
                </form>
            </div>
        </div>
    </section>

    <div style="height:1.55rem"></div>

    <section class="admin-dashboard-grid">
        <div class="admin-panel">
            <header class="admin-panel-head">
                <h2>Latest Reviews</h2>
                <a href="{{ route('admin.reviews.index') }}" class="admin-btn-outline">Manage Reviews</a>
            </header>

            <div class="admin-panel-body">
                <div class="admin-list">
                    @forelse($latestReviews as $review)
                        <div class="admin-list-row" style="align-items:flex-start">
                            <div>
                                <strong>{{ $review->movie?->title ?? 'Unknown Movie' }}</strong>
                                <span>
                                    {{ $review->user?->name ?: ($review->user?->username ?: 'User') }}
                                    · {{ optional($review->created_at)->format('Y-m-d') ?? '-' }}
                                </span>

                                <p style="margin-top:.55rem;color:rgba(237,233,254,.72);font-size:.82rem;line-height:1.45">
                                    {{ \Illuminate\Support\Str::limit($review->review_text, 160) }}
                                </p>
                            </div>

                            <form method="POST" action="{{ route('admin.reviews.destroy', $review) }}">
                                @csrf
                                @method('DELETE')

                                <button
                                    type="submit"
                                    onclick="return confirm('Hapus review ini?')"
                                    class="admin-btn-outline"
                                    style="color:#fecaca;border-color:rgba(248,113,113,.35)"
                                >
                                    Delete
                                </button>
                            </form>
                        </div>
                    @empty
                        <div class="admin-empty">Belum ada review terbaru.</div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="admin-panel">
            <header class="admin-panel-head">
                <h2>Review Moderation Alerts</h2>
                <a href="{{ route('admin.reviews.index') }}" class="admin-btn-outline">Manage Reviews</a>
            </header>

            <div class="admin-panel-body">
                <div class="admin-list">
                    @forelse($reviewAlerts as $review)
                        <div class="admin-list-row" style="align-items:flex-start">
                            <div>
                                <strong>{{ $review->movie?->title ?? 'Unknown Movie' }}</strong>
                                <span>
                                    {{ $review->user?->name ?: ($review->user?->username ?: 'User') }}
                                    · {{ $review->likes_count }} likes
                                </span>

                                <p style="margin-top:.55rem;color:rgba(237,233,254,.72);font-size:.82rem;line-height:1.45">
                                    {{ \Illuminate\Support\Str::limit($review->review_text, 180) }}
                                </p>
                            </div>

                            <form method="POST" action="{{ route('admin.reviews.destroy', $review) }}">
                                @csrf
                                @method('DELETE')

                                <button
                                    type="submit"
                                    onclick="return confirm('Hapus review ini?')"
                                    class="admin-btn-outline"
                                    style="color:#fecaca;border-color:rgba(248,113,113,.35)"
                                >
                                    Delete
                                </button>
                            </form>
                        </div>
                    @empty
                        <div class="admin-empty">Tidak ada review panjang yang perlu dicek.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </section>

    <div style="height:1.55rem"></div>

    <section class="admin-dashboard-grid">
        <div class="admin-panel">
            <header class="admin-panel-head">
                <h2>Admin Quick Actions</h2>
            </header>

            <div class="admin-panel-body">
                <div class="admin-list">
                    <a href="{{ route('admin.tmdb-import.index') }}" class="admin-list-row">
                        <strong>Import Movie from TMDB</strong>
                        <span>poster, banner, trailer, genre, actor, director</span>
                    </a>

                    <a href="{{ route('admin.movies.create') }}" class="admin-list-row">
                        <strong>Add Movie Manual</strong>
                        <span>input movie tanpa TMDB</span>
                    </a>

                    <a href="{{ route('admin.reviews.index') }}" class="admin-list-row">
                        <strong>Moderate Reviews</strong>
                        <span>hapus review yang tidak sesuai</span>
                    </a>

                    <a href="{{ route('admin.movies.index') }}" class="admin-list-row">
                        <strong>Manage Catalog</strong>
                        <span>edit data movie yang sudah ada</span>
                    </a>
                </div>
            </div>
        </div>
    </section>
</x-admin-layout>