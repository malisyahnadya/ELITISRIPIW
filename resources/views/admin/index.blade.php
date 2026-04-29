@php
    $stats = $stats ?? ['movies' => 0, 'users' => 0, 'reviews' => 0, 'ratings' => 0];
    $latestMovies = $latestMovies ?? collect();
    $genreLeaders = $genreLeaders ?? collect();
    $actorLeaders = $actorLeaders ?? collect();
    $directorLeaders = $directorLeaders ?? collect();
    $users = $users ?? collect();
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
                                <td><a href="{{ route('admin.movies.edit', $movie) }}">{{ $movie->title }}</a></td>
                                <td>{{ $movie->release_year ?? '-' }}</td>
                                <td>{{ $movie->duration_formatted }}</td>
                                <td>{{ $movie->ratings_avg_score ? number_format((float) $movie->ratings_avg_score, 1) : '-' }} ({{ $movie->ratings_count ?? 0 }})</td>
                                <td>{{ optional($movie->created_at)->format('Y-m-d') ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="admin-empty">Belum ada movie. Klik Add Movie untuk menambahkan data pertama.</td></tr>
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
</x-admin-layout>
