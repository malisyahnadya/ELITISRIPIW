@props(['overlay' => request()->routeIs('home')])

@php
    $navClass = $overlay
        ? 'absolute left-0 top-0 z-40 w-full bg-transparent py-5 text-white'
        : 'sticky left-0 top-0 z-40 w-full border-b border-white/10 bg-[#1c1527]/95 py-4 text-white shadow-[0_12px_40px_rgba(0,0,0,.24)] backdrop-blur';
@endphp

<nav x-data="{ open: false }" {{ $attributes->merge(['class' => $navClass]) }}>
    <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 sm:px-6 lg:px-8">
        <a href="{{ route('home') }}" class="shrink-0 text-lg font-extrabold tracking-[0.16em] text-white no-underline hover:text-white">
            ELITISRIPIW
        </a>

        <div class="hidden flex-1 justify-center px-6 md:flex">
            <div
                class="relative w-full max-w-md"
                x-data="liveSearch({ searchUrl: '{{ route('search') }}', suggestUrl: '{{ route('search.suggest') }}' })"
                @click.outside="close()"
            >
                <form method="GET" action="{{ route('search') }}">
                    <label class="sr-only" for="global-search-desktop">Search movies</label>
                    <input
                        id="global-search-desktop"
                        type="search"
                        name="q"
                        value="{{ request('q', '') }}"
                        x-model.trim="query"
                        @input="onInput"
                        @focus="onFocus"
                        autocomplete="off"
                        placeholder="Search movies..."
                        class="w-full rounded-full border-0 bg-white/20 py-2 pl-10 pr-4 text-sm text-white placeholder:text-white/70 shadow-inner outline-none transition focus:bg-white/30 focus:ring-2 focus:ring-white/25"
                    >
                    <span class="pointer-events-none absolute inset-y-0 left-4 flex items-center text-white/55">
                        <i class="bi bi-search"></i>
                    </span>
                </form>

                <div x-cloak x-show="open" x-transition class="absolute right-0 z-50 mt-2 w-[min(92vw,640px)] overflow-hidden rounded-lg border border-slate-200 bg-white text-slate-800 shadow-2xl">
                    <ul class="max-h-[32rem] overflow-y-auto">
                        <template x-if="loading">
                            <li class="px-3 py-2 text-xs text-slate-500">Memuat...</li>
                        </template>
                        <template x-if="!loading && error">
                            <li class="px-3 py-2 text-xs text-red-500" x-text="error"></li>
                        </template>
                        <template x-if="!loading && !error && hasSearched && results.length === 0">
                            <li class="px-3 py-2 text-xs text-slate-500">Film tidak ditemukan.</li>
                        </template>
                        <template x-for="movie in results" :key="movie.id ?? movie.url">
                            <li>
                                <a :href="movie.url" class="flex items-center gap-3 px-3 py-2 hover:bg-slate-100">
                                    <img :src="movie.poster_url || 'https://via.placeholder.com/48x72?text=No'" :alt="movie.title" class="h-12 w-8 rounded object-cover">
                                    <span class="min-w-0 flex-1">
                                        <span class="block truncate text-sm font-medium text-slate-700" x-text="movie.title"></span>
                                        <span class="block text-xs text-slate-500" x-text="`${movie.release_year ?? '-'} • ${movie.duration ?? '-'} • ${movie.average_score ?? '0.0'}/5`"></span>
                                    </span>
                                </a>
                            </li>
                        </template>
                    </ul>
                    <a :href="seeMoreUrl" class="block border-t border-slate-200 px-4 py-3 text-xs font-bold uppercase tracking-wide text-violet-700 hover:bg-slate-50">
                        See more results
                    </a>
                </div>
            </div>
        </div>

        <div class="hidden items-center gap-3 md:flex">
            <a href="{{ route('movies.index') }}" class="rounded-full border border-white/30 px-3 py-1.5 text-xs font-semibold text-white/90 transition hover:bg-white/15 hover:text-white">
                Movies
            </a>
            @auth
                @if (Auth::user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="rounded-full border border-white/30 px-3 py-1.5 text-xs font-semibold text-white/90 transition hover:bg-white/15 hover:text-white">
                        Admin
                    </a>
                @endif

                <a href="{{ route('profile.index') }}" class="rounded-full border border-white/30 px-3 py-1.5 text-xs font-semibold text-white/90 transition hover:bg-white/15 hover:text-white">
                    Profile
                </a>
            @else
                <a href="{{ route('login') }}" class="rounded-full border border-white/30 px-3 py-1.5 text-xs font-semibold text-white/90 transition hover:bg-white/15 hover:text-white">
                    Login
                </a>
                <a href="{{ route('register') }}" class="rounded-full border border-white/30 px-3 py-1.5 text-xs font-semibold text-white/90 transition hover:bg-white/15 hover:text-white">
                    Register
                </a>
                <span class="flex h-9 w-9 items-center justify-center rounded-full border border-white/20 bg-white/10 text-xl text-white">
                    <i class="bi bi-person-circle"></i>
                </span>
            @endauth
        </div>

        <button type="button" class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-white/20 bg-white/10 text-white md:hidden" @click="open = ! open" aria-label="Toggle navigation">
            <i class="bi" :class="open ? 'bi-x-lg' : 'bi-list'"></i>
        </button>
    </div>

    <div x-cloak x-show="open" x-transition class="mx-4 mt-4 rounded-2xl border border-white/10 bg-[#1c1527]/95 p-4 shadow-2xl backdrop-blur md:hidden">
        <div
            class="relative"
            x-data="liveSearch({ searchUrl: '{{ route('search') }}', suggestUrl: '{{ route('search.suggest') }}' })"
            @click.outside="close()"
        >
            <form method="GET" action="{{ route('search') }}">
                <label class="sr-only" for="global-search-mobile">Search movies</label>
                <input
                    id="global-search-mobile"
                    type="search"
                    name="q"
                    value="{{ request('q', '') }}"
                    x-model.trim="query"
                    @input="onInput"
                    @focus="onFocus"
                    autocomplete="off"
                    placeholder="Search movies..."
                    class="w-full rounded-full border-0 bg-white/20 py-2 pl-10 pr-4 text-sm text-white placeholder:text-white/70 shadow-inner outline-none transition focus:bg-white/30 focus:ring-2 focus:ring-white/25"
                >
                <span class="pointer-events-none absolute inset-y-0 left-4 flex items-center text-white/55">
                    <i class="bi bi-search"></i>
                </span>
            </form>

            <div x-cloak x-show="open" x-transition class="absolute left-0 right-0 z-50 mt-2 overflow-hidden rounded-lg border border-slate-200 bg-white text-slate-800 shadow-2xl">
                <ul class="max-h-80 overflow-y-auto">
                    <template x-if="loading">
                        <li class="px-3 py-2 text-xs text-slate-500">Memuat...</li>
                    </template>
                    <template x-if="!loading && error">
                        <li class="px-3 py-2 text-xs text-red-500" x-text="error"></li>
                    </template>
                    <template x-if="!loading && !error && hasSearched && results.length === 0">
                        <li class="px-3 py-2 text-xs text-slate-500">Film tidak ditemukan.</li>
                    </template>
                    <template x-for="movie in results" :key="movie.id ?? movie.url">
                        <li>
                            <a :href="movie.url" class="flex items-center gap-3 px-3 py-2 hover:bg-slate-100">
                                <img :src="movie.poster_url || 'https://via.placeholder.com/48x72?text=No'" :alt="movie.title" class="h-12 w-8 rounded object-cover">
                                <span class="min-w-0 flex-1">
                                    <span class="block truncate text-sm font-medium text-slate-700" x-text="movie.title"></span>
                                    <span class="block text-xs text-slate-500" x-text="`${movie.release_year ?? '-'} • ${movie.duration ?? '-'} • ${movie.average_score ?? '0.0'}/5`"></span>
                                </span>
                            </a>
                        </li>
                    </template>
                </ul>
                <a :href="seeMoreUrl" class="block border-t border-slate-200 px-4 py-3 text-xs font-bold uppercase tracking-wide text-violet-700 hover:bg-slate-50">
                    See more results
                </a>
            </div>
        </div>

        <div class="mt-4 flex flex-wrap items-center gap-2">
            <a href="{{ route('movies.index') }}" class="rounded-full border border-white/25 px-3 py-1.5 text-xs font-semibold text-white/90">Movies</a>
            @auth
                @if (Auth::user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="rounded-full border border-white/25 px-3 py-1.5 text-xs font-semibold text-white/90">Admin</a>
                @endif
                <a href="{{ route('profile.index') }}" class="rounded-full border border-white/25 px-3 py-1.5 text-xs font-semibold text-white/90">Profile</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="rounded-full border border-white/25 px-3 py-1.5 text-xs font-semibold text-white/90">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="rounded-full border border-white/25 px-3 py-1.5 text-xs font-semibold text-white/90">Login</a>
                <a href="{{ route('register') }}" class="rounded-full border border-white/25 px-3 py-1.5 text-xs font-semibold text-white/90">Register</a>
            @endauth
        </div>
    </div>
</nav>
