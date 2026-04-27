@php
    $stats = $stats ?? [
        'movies' => 0,
        'users' => 0,
        'reviews' => 0,
        'ratings' => 0,
    ];

    $healthStats = $healthStats ?? [
        'avg_rating' => 0,
        'watchlist_items' => 0,
        'admin_users' => 0,
        'avg_reviews_per_user' => 0,
    ];

    $latestMovies = $latestMovies ?? collect();
    $latestUsers = $latestUsers ?? collect();
    $genreLeaders = $genreLeaders ?? collect();
    $actorLeaders = $actorLeaders ?? collect();
    $directorLeaders = $directorLeaders ?? collect();

    $menuItems = [
        ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'pattern' => 'admin.dashboard', 'icon' => 'dashboard'],
        ['label' => 'Movies', 'route' => 'admin.movies.index', 'pattern' => 'admin.movies.*', 'icon' => 'movie'],
        ['label' => 'Genres', 'route' => 'admin.genres.index', 'pattern' => 'admin.genres.*', 'icon' => 'genre'],
        ['label' => 'Actors', 'route' => 'admin.actors.index', 'pattern' => 'admin.actors.*', 'icon' => 'actor'],
        ['label' => 'Directors', 'route' => 'admin.directors.index', 'pattern' => 'admin.directors.*', 'icon' => 'director'],
        ['label' => 'Users', 'route' => 'admin.users.index', 'pattern' => 'admin.users.*', 'icon' => 'users'],
    ];

    $actionLinks = [
        'movie_create' => Route::has('admin.movies.create') ? route('admin.movies.create') : '#',
        'genre_create' => Route::has('admin.genres.create') ? route('admin.genres.create') : '#',
        'actor_create' => Route::has('admin.actors.create') ? route('admin.actors.create') : '#',
        'director_create' => Route::has('admin.directors.create') ? route('admin.directors.create') : '#',
        'user_create' => Route::has('admin.users.create') ? route('admin.users.create') : '#',
    ];
@endphp

<x-app-layout>
    <div class="admin-board-font">
        <section class="admin-board min-h-screen w-full overflow-hidden rounded-none border-0 shadow-none">
            <div class="lg:grid lg:grid-cols-[17.5rem_minmax(0,1fr)] lg:min-h-screen">
                    <aside class="admin-sidebar p-5 text-slate-100 lg:p-6">
                        <div class="reveal-item" style="--delay: 80ms;">
                            <div class="flex items-center gap-3 rounded-2xl border border-slate-700 bg-slate-900/70 px-4 py-3">
                                <span class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-sky-500/20 text-sky-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h10M4 18h16" />
                                    </svg>
                                </span>
                                <div>
                                    <p class="text-sm font-semibold text-slate-200">ELITISRIPIW</p>
                                    <p class="text-xs text-slate-400">Admin Panel</p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-7 reveal-item" style="--delay: 120ms;">
                            <p class="px-2 text-[11px] font-semibold uppercase tracking-[0.24em] text-slate-400">Menu</p>
                            @include('components.admin-sidebar')
                        </div>

                        <div class="mt-7 reveal-item" style="--delay: 160ms;">
                            <p class="px-2 text-[11px] font-semibold uppercase tracking-[0.24em] text-slate-400">Site</p>
                            <a href="{{ route('home') }}" class="menu-link menu-link-idle mt-3">
                                <span class="menu-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                                    </svg>
                                </span>
                                <span class="text-sm font-medium">Back to Site</span>
                            </a>
                        </div>

                        <div class="mt-8 reveal-item rounded-2xl border border-slate-700 bg-slate-900/60 p-4" style="--delay: 200ms;">
                            <p class="text-[11px] uppercase tracking-[0.2em] text-slate-500">Logged in as</p>
                            <p class="mt-1 text-base font-semibold text-slate-200">{{ auth()->user()->username ?? 'admin' }}</p>
                            <p class="text-xs text-slate-400">{{ auth()->user()->email ?? '-' }}</p>
                        </div>
                    </aside>

                    <main class="admin-main p-5 sm:p-6 lg:p-8">
                        <div class="reveal-item mb-6 flex flex-wrap items-center justify-between gap-4" style="--delay: 120ms;">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Dashboard</p>
                                <h1 class="mt-1 text-3xl font-extrabold text-slate-900">Control Room</h1>
                            </div>

                            
                        </div>

                        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                            <article class="stat-card reveal-item" style="--delay: 180ms;">
                                <p class="stat-label">Movies</p>
                                <p class="stat-value">{{ $stats['movies'] ?? 0 }}</p>
                                <p class="stat-note">total catalog film</p>
                            </article>

                            <article class="stat-card reveal-item" style="--delay: 230ms;">
                                <p class="stat-label">Users</p>
                                <p class="stat-value">{{ $stats['users'] ?? 0 }}</p>
                                <p class="stat-note">akun terdaftar</p>
                            </article>

                            <article class="stat-card reveal-item" style="--delay: 280ms;">
                                <p class="stat-label">Reviews</p>
                                <p class="stat-value">{{ $stats['reviews'] ?? 0 }}</p>
                                <p class="stat-note">review aktif</p>
                            </article>

                            <article class="stat-card reveal-item" style="--delay: 330ms;">
                                <p class="stat-label">Ratings</p>
                                <p class="stat-value">{{ $stats['ratings'] ?? 0 }}</p>
                                <p class="stat-note">penilaian tersimpan</p>
                            </article>
                        </div>

                        <div class="mt-4 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                            <article class="micro-card reveal-item" style="--delay: 360ms;">
                                <p class="micro-label">Avg Rating</p>
                                <p class="micro-value">{{ number_format((float) ($healthStats['avg_rating'] ?? 0), 2) }}</p>
                                <p class="micro-note">out of 5</p>
                            </article>

                            <article class="micro-card reveal-item" style="--delay: 400ms;">
                                <p class="micro-label">Watchlist Items</p>
                                <p class="micro-value">{{ $healthStats['watchlist_items'] ?? 0 }}</p>
                                <p class="micro-note">semua user</p>
                            </article>

                            <article class="micro-card reveal-item" style="--delay: 440ms;">
                                <p class="micro-label">Admin Users</p>
                                <p class="micro-value">{{ $healthStats['admin_users'] ?? 0 }}</p>
                                <p class="micro-note">moderator aktif</p>
                            </article>

                            <article class="micro-card reveal-item" style="--delay: 480ms;">
                                <p class="micro-label">Avg Reviews/User</p>
                                <p class="micro-value">{{ number_format((float) ($healthStats['avg_reviews_per_user'] ?? 0), 2) }}</p>
                                <p class="micro-note">engagement</p>
                            </article>
                        </div>

                        <div class="mt-6 grid gap-6 xl:grid-cols-3">
                            <section class="panel-card reveal-item xl:col-span-2" style="--delay: 520ms;">
                                <header class="panel-head">
                                    <h2>Recent Movies</h2>
                                    <a href="{{ $actionLinks['movie_create'] }}" class="panel-action {{ $actionLinks['movie_create'] === '#' ? 'panel-action-disabled' : '' }}">+ Add Movie</a>
                                </header>

                                <div class="overflow-x-auto">
                                    <table class="min-w-full text-sm text-slate-700">
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
                                            @forelse ($latestMovies as $movie)
                                                @php
                                                    $duration = (int) ($movie->duration_minutes ?? 0);
                                                    $durationHours = intdiv($duration, 60);
                                                    $durationMinutes = $duration % 60;
                                                    $durationLabel = $durationHours > 0
                                                        ? ($durationMinutes > 0 ? $durationHours . 'h ' . $durationMinutes . 'm' : $durationHours . 'h')
                                                        : $durationMinutes . 'm';
                                                @endphp
                                                <tr>
                                                    <td class="font-semibold text-slate-800">{{ $movie->title }}</td>
                                                    <td>{{ $movie->release_year ?? '-' }}</td>
                                                    <td>{{ $durationLabel }}</td>
                                                    <td>
                                                        {{ $movie->ratings_avg_score !== null ? number_format((float) $movie->ratings_avg_score, 1) : '-' }}
                                                        <span class="text-xs text-slate-400">({{ $movie->ratings_count }})</span>
                                                    </td>
                                                    <td>{{ optional($movie->created_at)->format('Y-m-d') }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="empty-cell">Belum ada movie tersimpan.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </section>

                            <section class="panel-card reveal-item" style="--delay: 560ms;">
                                <header class="panel-head">
                                    <h2>Recent Users</h2>
                                    <a href="{{ $actionLinks['user_create'] }}" class="panel-action {{ $actionLinks['user_create'] === '#' ? 'panel-action-disabled' : '' }}">+ Add User</a>
                                </header>

                                <div class="space-y-2.5 p-4">
                                    @forelse ($latestUsers as $user)
                                        <article class="row-chip">
                                            <div>
                                                <p class="font-semibold text-slate-800">{{ $user->username }}</p>
                                                <p class="text-xs text-slate-500">{{ $user->email }}</p>
                                            </div>
                                            <div class="text-right">
                                                <span class="rounded-full px-2 py-1 text-[11px] font-bold {{ $user->role === 'admin' ? 'bg-rose-100 text-rose-600' : 'bg-slate-200 text-slate-600' }}">
                                                    {{ $user->role }}
                                                </span>
                                                <p class="mt-2 text-[11px] text-slate-500">
                                                    R {{ $user->reviews_count }} | RT {{ $user->ratings_count }}
                                                </p>
                                            </div>
                                        </article>
                                    @empty
                                        <p class="empty-cell">Belum ada data user.</p>
                                    @endforelse
                                </div>
                            </section>
                        </div>

                        <div class="mt-6 grid gap-6 md:grid-cols-2 xl:grid-cols-3">
                            <section class="panel-card reveal-item" style="--delay: 610ms;">
                                <header class="panel-head">
                                    <h2>Genres</h2>
                                    <a href="{{ $actionLinks['genre_create'] }}" class="panel-action {{ $actionLinks['genre_create'] === '#' ? 'panel-action-disabled' : '' }}">+ Add Genre</a>
                                </header>
                                <div class="space-y-2.5 p-4">
                                    @forelse ($genreLeaders as $genre)
                                        <div class="row-chip row-chip-tight">
                                            <span class="font-semibold text-slate-800">{{ $genre->name }}</span>
                                            <span class="text-xs text-slate-500">{{ $genre->movies_count }} movies</span>
                                        </div>
                                    @empty
                                        <p class="empty-cell">Belum ada genre.</p>
                                    @endforelse
                                </div>
                            </section>

                            <section class="panel-card reveal-item" style="--delay: 650ms;">
                                <header class="panel-head">
                                    <h2>Actors</h2>
                                    <a href="{{ $actionLinks['actor_create'] }}" class="panel-action {{ $actionLinks['actor_create'] === '#' ? 'panel-action-disabled' : '' }}">+ Add Actor</a>
                                </header>
                                <div class="space-y-2.5 p-4">
                                    @forelse ($actorLeaders as $actor)
                                        <div class="row-chip row-chip-tight">
                                            <span class="font-semibold text-slate-800">{{ $actor->name }}</span>
                                            <span class="text-xs text-slate-500">{{ $actor->movies_count }} movies</span>
                                        </div>
                                    @empty
                                        <p class="empty-cell">Belum ada actor.</p>
                                    @endforelse
                                </div>
                            </section>

                            <section class="panel-card reveal-item md:col-span-2 xl:col-span-1" style="--delay: 690ms;">
                                <header class="panel-head">
                                    <h2>Directors</h2>
                                    <a href="{{ $actionLinks['director_create'] }}" class="panel-action {{ $actionLinks['director_create'] === '#' ? 'panel-action-disabled' : '' }}">+ Add Director</a>
                                </header>
                                <div class="space-y-2.5 p-4">
                                    @forelse ($directorLeaders as $director)
                                        <div class="row-chip row-chip-tight">
                                            <span class="font-semibold text-slate-800">{{ $director->name }}</span>
                                            <span class="text-xs text-slate-500">{{ $director->movies_count }} movies</span>
                                        </div>
                                    @empty
                                        <p class="empty-cell">Belum ada director.</p>
                                    @endforelse
                                </div>
                            </section>
                        </div>
                    </main>
            </div>
        </section>
    </div>

    <style>
        @import url('https://fonts.bunny.net/css?family=manrope:400,500,600,700,800&display=swap');

        :root {
            --admin-bg: #1a1430;
            --admin-surface: #332754;
            --admin-surface-soft: #3d2f63;
            --admin-border: #5d4c89;
            --admin-shadow: rgba(9, 5, 20, 0.55);
            --admin-text: #f2ecff;
            --admin-text-soft: #b9abd9;
            --admin-accent: #9e88db;
            --admin-accent-dark: #8a74cb;
            --admin-sidebar: #171229;
            --admin-sidebar-deep: #130f24;
            --admin-sidebar-border: #3b2f63;
            --admin-sidebar-text: #c5b7e7;
            --admin-sidebar-text-strong: #f8f5ff;
        }

        .admin-board-font {
            font-family: 'Manrope', sans-serif;
        }

        .admin-board {
            background:
                radial-gradient(860px 420px at 100% 0%, rgba(166, 140, 240, 0.18), transparent 65%),
                linear-gradient(180deg, #201738 0%, #170f2a 100%);
            border: 0;
            box-shadow: none;
        }

        .admin-sidebar {
            background: linear-gradient(180deg, var(--admin-sidebar) 0%, var(--admin-sidebar-deep) 100%);
            border-right: 1px solid var(--admin-sidebar-border);
        }

        .admin-main {
            background: linear-gradient(180deg, #2a2044 0%, #231b3a 100%);
        }

        .menu-link {
            display: flex;
            align-items: center;
            gap: 0.72rem;
            border-radius: 0.9rem;
            padding: 0.58rem 0.72rem;
            transition: all 160ms ease;
        }

        .menu-link-idle {
            color: var(--admin-sidebar-text);
            border: 1px solid transparent;
        }

        .menu-link-idle:hover {
            color: var(--admin-sidebar-text-strong);
            background: rgba(76, 61, 115, 0.72);
            border-color: rgba(148, 126, 210, 0.46);
        }

        .menu-link-active {
            color: #ffffff;
            background: linear-gradient(90deg, var(--admin-accent) 0%, var(--admin-accent-dark) 100%);
            border: 1px solid rgba(194, 178, 238, 0.75);
            box-shadow: 0 6px 16px rgba(72, 52, 122, 0.45);
        }

        .menu-link-disabled {
            opacity: 0.56;
            pointer-events: none;
        }

        .menu-icon {
            display: inline-flex;
            height: 1.8rem;
            width: 1.8rem;
            align-items: center;
            justify-content: center;
            border-radius: 0.55rem;
            background: rgba(93, 76, 137, 0.45);
            color: #ddd2ff;
            transition: all 160ms ease;
        }

        .menu-link:hover .menu-icon,
        .menu-icon-active {
            background: rgba(189, 166, 242, 0.32);
            color: #f8f5ff;
        }

        .stat-card {
            border-radius: 1rem;
            border: 1px solid var(--admin-border);
            background: var(--admin-surface-soft);
            padding: 1rem;
            box-shadow: 0 8px 18px var(--admin-shadow);
        }

        .stat-label {
            font-size: 0.8rem;
            font-weight: 700;
            letter-spacing: 0.03em;
            color: var(--admin-text-soft);
        }

        .stat-value {
            margin-top: 0.4rem;
            font-size: 2rem;
            font-weight: 800;
            line-height: 1;
            color: #f6f3ff;
        }

        .stat-note {
            margin-top: 0.45rem;
            font-size: 0.75rem;
            color: var(--admin-text-soft);
        }

        .micro-card {
            border-radius: 0.9rem;
            border: 1px solid var(--admin-border);
            background: var(--admin-surface);
            padding: 0.85rem 1rem;
            box-shadow: 0 7px 14px rgba(8, 4, 20, 0.35);
        }

        .micro-label {
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--admin-text-soft);
        }

        .micro-value {
            margin-top: 0.3rem;
            font-size: 1.42rem;
            font-weight: 800;
            color: #efe9ff;
        }

        .micro-note {
            font-size: 0.72rem;
            color: var(--admin-text-soft);
        }

        .panel-card {
            overflow: hidden;
            border-radius: 1rem;
            border: 1px solid var(--admin-border);
            background: var(--admin-surface);
            box-shadow: 0 10px 20px rgba(8, 4, 20, 0.38);
        }

        .panel-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.6rem;
            border-bottom: 1px solid #5b4a86;
            background: #41326a;
            padding: 0.95rem 1rem;
        }

        .panel-head h2 {
            font-size: 1rem;
            font-weight: 700;
            color: var(--admin-text);
        }

        .panel-action {
            display: inline-flex;
            align-items: center;
            border-radius: 999px;
            border: 1px solid #c8b8f0;
            background: rgba(188, 172, 238, 0.2);
            padding: 0.32rem 0.7rem;
            font-size: 0.72rem;
            font-weight: 700;
            color: #f8f5ff;
            transition: all 160ms ease;
        }

        .panel-action:hover {
            border-color: #d8cbf6;
            color: #ffffff;
            background: rgba(188, 172, 238, 0.34);
        }

        .panel-action-disabled {
            opacity: 0.5;
            pointer-events: none;
        }

        table thead {
            background: #3e3164;
        }

        table th {
            text-align: left;
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.07em;
            color: var(--admin-text-soft);
            padding: 0.72rem 0.9rem;
        }

        table td {
            padding: 0.72rem 0.9rem;
            border-top: 1px solid #56487f;
            white-space: nowrap;
        }

        .row-chip {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
            border-radius: 0.75rem;
            border: 1px solid #5a4b84;
            background: #44366d;
            padding: 0.68rem 0.75rem;
        }

        .row-chip-tight {
            padding-top: 0.55rem;
            padding-bottom: 0.55rem;
        }

        .empty-cell {
            padding: 1rem;
            text-align: center;
            font-size: 0.82rem;
            color: var(--admin-text-soft);
        }

        .admin-sidebar .text-slate-200 {
            color: #f3ecff !important;
        }

        .admin-sidebar .text-slate-400 {
            color: #b4a7d8 !important;
        }

        .admin-sidebar .text-slate-500 {
            color: #8f82b5 !important;
        }

        .admin-sidebar .border-slate-700 {
            border-color: #3f3264 !important;
        }

        .admin-sidebar .bg-slate-900\/70,
        .admin-sidebar .bg-slate-900\/60 {
            background-color: rgba(23, 17, 40, 0.74) !important;
        }

        .admin-sidebar .bg-sky-500\/20 {
            background-color: rgba(153, 125, 226, 0.3) !important;
        }

        .admin-sidebar .text-sky-300 {
            color: #e5d9ff !important;
        }

        .admin-main .text-slate-900,
        .admin-main .text-slate-800 {
            color: #f2ecff !important;
        }

        .admin-main .text-slate-700 {
            color: #d8cdf6 !important;
        }

        .admin-main .text-slate-500,
        .admin-main .text-slate-400 {
            color: #b9add9 !important;
        }

        .admin-main .border-slate-300 {
            border-color: #5d4f87 !important;
        }

        .admin-main .bg-white {
            background-color: #403169 !important;
        }

        .admin-main .shadow-sm {
            box-shadow: 0 8px 16px rgba(8, 4, 20, 0.36) !important;
        }

        .admin-main .bg-rose-100 {
            background-color: #5e2f53 !important;
        }

        .admin-main .text-rose-600 {
            color: #ffd8ef !important;
        }

        .admin-main .bg-slate-200 {
            background-color: #5b4a88 !important;
        }

        .admin-main .text-slate-600 {
            color: #e7dcff !important;
        }

        .reveal-item {
            opacity: 0;
            transform: translateY(12px);
            animation: revealIn 0.55s ease forwards;
            animation-delay: var(--delay, 0ms);
        }

        @keyframes revealIn {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</x-app-layout>
