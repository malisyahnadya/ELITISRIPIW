<x-app-layout>
    <div class="bg-slate-950 text-slate-100">
        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            <section>
                <h2 class="mb-4 text-xl font-bold tracking-wide text-cyan-300">Sedang Populer</h2>

                @if ($popularMovies->isNotEmpty())
                    <div id="popular-carousel" class="relative overflow-hidden rounded-2xl border border-slate-700 bg-slate-900 shadow-2xl">
                        @foreach ($popularMovies as $index => $movie)
                            <article class="carousel-item {{ $index === 0 ? 'block' : 'hidden' }} relative min-h-[320px] sm:min-h-[420px]" data-index="{{ $index }}">
                                <img
                                    src="{{ $movie->banner_url ?? $movie->poster_url ?? 'https://via.placeholder.com/1200x480?text=No+Banner' }}"
                                    alt="{{ $movie->title }}"
                                    class="h-full w-full object-cover"
                                >

                                <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/60 to-transparent"></div>

                                <div class="absolute bottom-0 left-0 right-0 p-5 sm:p-8">
                                    <p class="mb-2 text-xs uppercase tracking-[0.22em] text-cyan-300">Top Rated</p>
                                    <h3 class="text-2xl font-extrabold sm:text-4xl">{{ $movie->title }}</h3>
                                    <div class="mt-3 flex flex-wrap gap-2 text-sm text-slate-200">
                                        <span class="rounded-full bg-slate-800/80 px-3 py-1">{{ $movie->release_year }}</span>
                                        <span class="rounded-full bg-slate-800/80 px-3 py-1">{{ number_format($movie->average_score, 1) }} / 10</span>
                                        <span class="rounded-full bg-slate-800/80 px-3 py-1">{{ $movie->duration_formatted }}</span>
                                    </div>
                                </div>
                            </article>
                        @endforeach

                        <div class="absolute bottom-4 right-4 flex gap-2">
                            <button type="button" data-carousel-prev class="rounded-md bg-slate-800/80 px-3 py-2 text-sm hover:bg-slate-700">Prev</button>
                            <button type="button" data-carousel-next class="rounded-md bg-cyan-600 px-3 py-2 text-sm hover:bg-cyan-500">Next</button>
                        </div>
                    </div>
                @else
                    <div class="rounded-2xl border border-dashed border-slate-700 bg-slate-900 p-8 text-center text-slate-300">
                        Belum ada film populer untuk ditampilkan.
                    </div>
                @endif
            </section>

            <section class="mt-12">
                <h2 class="mb-4 text-xl font-bold tracking-wide text-amber-300">Rekomendasi Berdasarkan Aktivitas Rating & Review</h2>

                <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-6">
                    @forelse ($recommendedMovies as $movie)
                        <article class="overflow-hidden rounded-xl border border-slate-700 bg-slate-900 shadow">
                            <img
                                src="{{ $movie->poster_url ?? 'https://via.placeholder.com/320x480?text=No+Poster' }}"
                                alt="{{ $movie->title }}"
                                class="h-56 w-full object-cover"
                            >
                            <div class="p-3">
                                <h3 class="truncate text-sm font-semibold">{{ $movie->title }}</h3>
                                <p class="mt-1 text-xs text-slate-400">{{ $movie->release_year }} • {{ $movie->duration_formatted }}</p>
                                <p class="mt-2 text-xs text-cyan-300">⭐ {{ number_format($movie->average_score, 1) }} • {{ $movie->ratings_count }} rating</p>
                            </div>
                        </article>
                    @empty
                        <p class="col-span-full rounded-xl border border-dashed border-slate-700 bg-slate-900 p-6 text-center text-slate-300">
                            Belum ada rekomendasi film.
                        </p>
                    @endforelse
                </div>
            </section>

            <section class="mt-12">
                <h2 class="mb-4 text-xl font-bold tracking-wide text-emerald-300">Watchlist Kamu</h2>

                @auth
                    <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-6">
                        @forelse ($userWatchlist as $item)
                            @if ($item->movie)
                                <article class="overflow-hidden rounded-xl border border-slate-700 bg-slate-900 shadow">
                                    <img
                                        src="{{ $item->movie->poster_url ?? 'https://via.placeholder.com/320x480?text=No+Poster' }}"
                                        alt="{{ $item->movie->title }}"
                                        class="h-56 w-full object-cover"
                                    >
                                    <div class="p-3">
                                        <h3 class="truncate text-sm font-semibold">{{ $item->movie->title }}</h3>
                                        <p class="mt-1 text-xs text-slate-400">{{ ucfirst(str_replace('_', ' ', $item->status)) }}</p>
                                    </div>
                                </article>
                            @endif
                        @empty
                            <p class="col-span-full rounded-xl border border-dashed border-slate-700 bg-slate-900 p-6 text-center text-slate-300">
                                Watchlist kamu masih kosong.
                            </p>
                        @endforelse
                    </div>
                @else
                    <div class="rounded-xl border border-slate-700 bg-slate-900 p-6 text-center">
                        <p class="mb-4 text-slate-300">Login dulu untuk lihat dan kelola watchlist pribadi.</p>
                        <div class="flex justify-center gap-3">
                            <a href="{{ route('login') }}" class="rounded-lg bg-cyan-600 px-4 py-2 text-sm font-semibold hover:bg-cyan-500">Login</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="rounded-lg border border-slate-500 px-4 py-2 text-sm font-semibold hover:bg-slate-800">Register</a>
                            @endif
                        </div>
                    </div>
                @endauth
            </section>

            <footer class="mt-14 border-t border-slate-800 pb-10 pt-6 text-center text-xs text-slate-400">
                ELITISRIPIW • Jelajahi film populer, beri rating, dan simpan watchlist favoritmu.
            </footer>
        </div>
    </div>

    @if ($popularMovies->count() > 1)
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const container = document.getElementById('popular-carousel');
                if (!container) return;

                const items = container.querySelectorAll('.carousel-item');
                const prevButton = container.querySelector('[data-carousel-prev]');
                const nextButton = container.querySelector('[data-carousel-next]');
                let current = 0;

                const showSlide = (index) => {
                    items.forEach((item, i) => {
                        item.classList.toggle('hidden', i !== index);
                        item.classList.toggle('block', i === index);
                    });
                };

                const next = () => {
                    current = (current + 1) % items.length;
                    showSlide(current);
                };

                const prev = () => {
                    current = (current - 1 + items.length) % items.length;
                    showSlide(current);
                };

                nextButton?.addEventListener('click', next);
                prevButton?.addEventListener('click', prev);

                setInterval(next, 5500);
            });
        </script>
    @endif
</x-app-layout>