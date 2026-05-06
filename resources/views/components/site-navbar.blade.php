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
            {{-- Wrapper komponen live search untuk tampilan desktop. --}}
            <div
                class="relative w-full max-w-md"
                {{-- Inisialisasi state Alpine live search dengan URL halaman hasil dan URL endpoint saran. --}}
                x-data="liveSearch({ searchUrl: '{{ route('search') }}', suggestUrl: '{{ route('search.suggest') }}' })"
                {{-- Tutup dropdown hasil saat klik di luar area pencarian. --}}
                @click.outside="close()"
            >
                {{-- Form fallback: submit normal ke halaman search jika user menekan Enter. --}}
                <form method="GET" action="{{ route('search') }}">
                    {{-- Label aksesibilitas (disembunyikan visual) untuk input search desktop. --}}
                    <label class="sr-only" for="global-search-desktop">Search movies</label>
                    <input
                        {{-- ID input desktop agar terhubung ke label. --}}
                        id="global-search-desktop"
                        {{-- Tipe input pencarian. --}}
                        type="search"
                        {{-- Nama query string yang dipakai backend: q. --}}
                        name="q"
                        {{-- Isi default dari query saat ini agar state tetap saat reload. --}}
                        value="{{ request('q', '') }}"
                        {{-- Two-way binding Alpine: nilai input disimpan ke properti query. --}}
                        x-model.trim="query"
                        {{-- Trigger live search setiap input berubah (dengan debounce di JS). --}}
                        @input="onInput"
                        {{-- Saat input fokus, tampilkan dropdown jika syarat terpenuhi. --}}
                        @focus="onFocus"
                        {{-- Matikan autocomplete browser agar tidak bentrok dengan suggestion custom. --}}
                        autocomplete="off"
                        {{-- Placeholder untuk petunjuk user. --}}
                        placeholder="Search movies..."
                        class="w-full rounded-full border-0 bg-white/20 py-2 pl-10 pr-4 text-sm text-white placeholder:text-white/70 shadow-inner outline-none transition focus:bg-white/30 focus:ring-2 focus:ring-white/25"
                    >
                    {{-- Ikon search di dalam input (dekoratif, tidak bisa diklik). --}}
                    <span class="pointer-events-none absolute inset-y-0 left-4 flex items-center text-white/55">
                        <i class="bi bi-search"></i>
                    </span>
                </form>

                {{-- Container dropdown hasil live search (muncul saat open = true). --}}
                <div x-cloak x-show="open" x-transition class="absolute right-0 z-50 mt-2 w-[min(92vw,640px)] overflow-hidden rounded-lg border border-slate-200 bg-white text-slate-800 shadow-2xl">
                    {{-- List hasil suggestion dengan batas tinggi dan scroll. --}}
                    <ul class="max-h-[32rem] overflow-y-auto">
                        {{-- State loading saat request suggest sedang berjalan. --}}
                        <template x-if="loading">
                            <li class="px-3 py-2 text-xs text-slate-500">Memuat...</li>
                        </template>
                        {{-- State error saat fetch gagal. --}}
                        <template x-if="!loading && error">
                            <li class="px-3 py-2 text-xs text-red-500" x-text="error"></li>
                        </template>
                        {{-- State empty saat pencarian selesai tapi tidak ada hasil. --}}
                        <template x-if="!loading && !error && hasSearched && results.length === 0">
                            <li class="px-3 py-2 text-xs text-slate-500">Film tidak ditemukan.</li>
                        </template>
                        {{-- Loop semua hasil suggestion dari response JSON. --}}
                        <template x-for="movie in results" :key="movie.id ?? movie.url">
                            <li>
                                {{-- Link ke halaman detail movie dari item suggestion. --}}
                                <a :href="movie.url" class="flex items-center gap-3 px-3 py-2 hover:bg-slate-100">
                                    {{-- Thumbnail poster film (fallback placeholder jika kosong). --}}
                                    <img :src="movie.poster_url || 'https://via.placeholder.com/48x72?text=No'" :alt="movie.title" class="h-12 w-8 rounded object-cover">
                                    <span class="min-w-0 flex-1">
                                        {{-- Judul film. --}}
                                        <span class="block truncate text-sm font-medium text-slate-700" x-text="movie.title"></span>
                                        {{-- Metadata film: tahun, durasi, dan skor. --}}
                                        <span class="block text-xs text-slate-500" x-text="`${movie.release_year ?? '-'} • ${movie.duration ?? '-'} • ${movie.average_score ?? '0.0'}/5`"></span>
                                    </span>
                                </a>
                            </li>
                        </template>
                    </ul>
                    {{-- CTA ke halaman hasil penuh berdasarkan query aktif. --}}
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
            @endauth
        </div>

        <button type="button" class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-white/20 bg-white/10 text-white md:hidden" @click="open = ! open" aria-label="Toggle navigation">
            <i class="bi" :class="open ? 'bi-x-lg' : 'bi-list'"></i>
        </button>
    </div>

    <div x-cloak x-show="open" x-transition class="mx-4 mt-4 rounded-2xl border border-white/10 bg-[#1c1527]/95 p-4 shadow-2xl backdrop-blur md:hidden">
        {{-- Wrapper komponen live search untuk tampilan mobile. --}}
        <div
            class="relative"
            {{-- Inisialisasi live search terpisah untuk mobile. --}}
            x-data="liveSearch({ searchUrl: '{{ route('search') }}', suggestUrl: '{{ route('search.suggest') }}' })"
            {{-- Tutup dropdown mobile saat klik di luar area pencarian. --}}
            @click.outside="close()"
        >
            {{-- Form fallback mobile untuk submit pencarian normal. --}}
            <form method="GET" action="{{ route('search') }}">
                {{-- Label aksesibilitas untuk input search mobile. --}}
                <label class="sr-only" for="global-search-mobile">Search movies</label>
                <input
                    {{-- ID input mobile agar terhubung ke label. --}}
                    id="global-search-mobile"
                    {{-- Tipe input pencarian. --}}
                    type="search"
                    {{-- Nama query string yang dipakai backend: q. --}}
                    name="q"
                    {{-- Isi default query saat ini supaya konsisten antar halaman. --}}
                    value="{{ request('q', '') }}"
                    {{-- Two-way binding Alpine untuk nilai query. --}}
                    x-model.trim="query"
                    {{-- Trigger live search ketika user mengetik. --}}
                    @input="onInput"
                    {{-- Buka dropdown saat input fokus jika query valid. --}}
                    @focus="onFocus"
                    {{-- Nonaktifkan autocomplete browser. --}}
                    autocomplete="off"
                    {{-- Placeholder pencarian. --}}
                    placeholder="Search movies..."
                    class="w-full rounded-full border-0 bg-white/20 py-2 pl-10 pr-4 text-sm text-white placeholder:text-white/70 shadow-inner outline-none transition focus:bg-white/30 focus:ring-2 focus:ring-white/25"
                >
                {{-- Ikon search dekoratif di input mobile. --}}
                <span class="pointer-events-none absolute inset-y-0 left-4 flex items-center text-white/55">
                    <i class="bi bi-search"></i>
                </span>
            </form>

            {{-- Dropdown hasil live search untuk mobile. --}}
            <div x-cloak x-show="open" x-transition class="absolute left-0 right-0 z-50 mt-2 overflow-hidden rounded-lg border border-slate-200 bg-white text-slate-800 shadow-2xl">
                {{-- List hasil suggestion mobile. --}}
                <ul class="max-h-80 overflow-y-auto">
                    {{-- State loading saat request berjalan. --}}
                    <template x-if="loading">
                        <li class="px-3 py-2 text-xs text-slate-500">Memuat...</li>
                    </template>
                    {{-- State error saat request gagal. --}}
                    <template x-if="!loading && error">
                        <li class="px-3 py-2 text-xs text-red-500" x-text="error"></li>
                    </template>
                    {{-- State tidak ada hasil setelah pencarian. --}}
                    <template x-if="!loading && !error && hasSearched && results.length === 0">
                        <li class="px-3 py-2 text-xs text-slate-500">Film tidak ditemukan.</li>
                    </template>
                    {{-- Iterasi hasil dari endpoint suggest. --}}
                    <template x-for="movie in results" :key="movie.id ?? movie.url">
                        <li>
                            {{-- Link ke halaman detail film dari suggestion item. --}}
                            <a :href="movie.url" class="flex items-center gap-3 px-3 py-2 hover:bg-slate-100">
                                {{-- Poster film dengan fallback placeholder. --}}
                                <img :src="movie.poster_url || 'https://via.placeholder.com/48x72?text=No'" :alt="movie.title" class="h-12 w-8 rounded object-cover">
                                <span class="min-w-0 flex-1">
                                    {{-- Judul film. --}}
                                    <span class="block truncate text-sm font-medium text-slate-700" x-text="movie.title"></span>
                                    {{-- Ringkasan info film. --}}
                                    <span class="block text-xs text-slate-500" x-text="`${movie.release_year ?? '-'} • ${movie.duration ?? '-'} • ${movie.average_score ?? '0.0'}/5`"></span>
                                </span>
                            </a>
                        </li>
                    </template>
                </ul>
                {{-- Tombol menuju halaman search lengkap berdasarkan query aktif. --}}
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
