<x-app-layout>
    <div class="bg-slate-950 text-slate-100">
        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            <h1>Halaman Utama</h1>
            <h2>Perhatikan fungsi yang sudah ada di controller, dan routing di web.php</h2>
            {{-- SECTION 1: POPULAR CAROUSEL --}}
            <!-- Carouselnya masih pakai Carousel custom vanilla js + tailwind -->
            <section>
                @if ($popularMovies->isNotEmpty())
                    <div id="popular-carousel" class="relative overflow-hidden rounded-2xl border border-slate-700 bg-slate-900 shadow-2xl">
                        @foreach ($popularMovies as $index => $movie)
                            {{-- Index 0 otomatis tampil (block), sisanya sembunyi (hidden) --}}
                            <article class="carousel-item {{ $index === 0 ? 'block' : 'hidden' }} relative min-h-[320px] sm:min-h-[420px]" data-index="{{ $index }}">
                                {{-- Banner_url & poster_url adalah accessor dari Model Movie --}}
                                <img
                                    src="{{ $movie->banner_url ?? $movie->poster_url ?? 'https://via.placeholder.com/1200x480?text=No+Banner' }}"
                                    alt="{{ $movie->title }}"
                                    class="h-full w-full object-cover"
                                >

                                {{-- Overlay gradien biar teks tetep kebaca --}}
                                <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/60 to-transparent"></div>

                                <div class="absolute bottom-0 left-0 right-0 p-5 sm:p-8">
                                    <p class="mb-2 text-xs uppercase tracking-[0.22em] text-cyan-300">Top Rated</p>
                                    <h3 class="text-2xl font-extrabold sm:text-4xl">{{ $movie->title }}</h3>
                                    
                                    <div class="mt-3 flex flex-wrap gap-2 text-sm text-slate-200">
                                        <span class="rounded-full bg-slate-800/80 px-3 py-1">{{ $movie->release_year }}</span>
                                        <span class="rounded-full bg-slate-800/80 px-3 py-1">{{ number_format($movie->average_score, 1) }} / 5</span>
                                        
                                        {{-- Loop buat nampilin icon bintang (Font: Bootstrap Icons) --}}
                                        <span class="rounded-full bg-slate-800/80 px-3 py-1">
                                            @foreach ($movie->average_score_star_icons as $icon)
                                                <i class="bi {{ $icon }} text-amber-300"></i>
                                            @endforeach
                                        </span>
                                        <span class="rounded-full bg-slate-800/80 px-3 py-1">{{ $movie->duration_formatted }}</span>
                                    </div>
                                </div>
                            </article>
                        @endforeach

                        {{-- Navigasi Carousel (Triggered by JS di bawah) --}}
                        <div class="absolute bottom-4 right-4 flex gap-2">
                            <button type="button" data-carousel-prev class="rounded-md bg-slate-800/80 px-3 py-2 text-sm hover:bg-slate-700">Prev</button>
                            <button type="button" data-carousel-next class="rounded-md bg-cyan-600 px-3 py-2 text-sm hover:bg-cyan-500">Next</button>
                        </div>
                    </div>
                @endif
            </section>

            {{-- SECTION 2: RECOMMENDED MOVIES --}}
            <section class="mt-12">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-bold tracking-wide text-amber-300">Rekomendasi Berdasarkan Aktivitas</h2>
                    
                    {{-- Tombol See More muncul kalau variabel $hasMoreRecommended bernilai true --}}
                    @if($hasMoreRecommended)
                        <a href="{{ route('search') }}" class="text-sm font-semibold text-cyan-400 hover:text-cyan-300">
                            See More <i class="bi bi-arrow-right"></i>
                        </a>
                    @endif
                </div>

                <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-6">
                    @forelse ($recommendedMovies as $movie)
                        <article class="overflow-hidden rounded-xl border border-slate-700 bg-slate-900 shadow transition hover:border-cyan-500">
                            <img src="{{ $movie->poster_url }}" alt="{{ $movie->title }}" class="h-56 w-full object-cover">
                            <div class="p-3">
                                <h3 class="truncate text-sm font-semibold">{{ $movie->title }}</h3>
                                <p class="mt-1 text-xs text-slate-400">{{ $movie->release_year }} • {{ $movie->duration_formatted }}</p>
                                <p class="mt-2 text-xs text-cyan-300">
                                    {{ number_format($movie->average_score, 1) }}/5
                                    <span class="ml-1">
                                        @foreach ($movie->average_score_star_icons as $icon)
                                            <i class="bi {{ $icon }} text-amber-300"></i>
                                        @endforeach
                                    </span>
                                </p>
                            </div>
                        </article>
                    @empty
                        <p class="col-span-full text-center text-slate-500">Belum ada rekomendasi.</p>
                    @endforelse
                </div>
            </section>

            {{-- SECTION 3: WATCHLIST (USER ONLY) --}}
            <section class="mt-12">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-bold tracking-wide text-emerald-300">Watchlist Kamu</h2>
                    {{-- Link See More khusus ke halaman My Watchlist --}}
                    @auth
                        @if($hasMoreWatchlist)
                            <a href="{{ route('watchlist.index') }}" class="text-sm font-semibold text-emerald-400 hover:text-emerald-300">
                                See More <i class="bi bi-arrow-right"></i>
                            </a>
                        @endif
                    @endauth
                </div>

                @auth
                    <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-6">
                        @forelse ($userWatchlist as $item)
                            {{-- Akses data film lewat relasi $item->movie --}}
                            @if ($item->movie)
                                <article class="overflow-hidden rounded-xl border border-slate-700 bg-slate-900 shadow">
                                    <img src="{{ $item->movie->poster_url }}" class="h-56 w-full object-cover">
                                    <div class="p-3">
                                        <h3 class="truncate text-sm font-semibold">{{ $item->movie->title }}</h3>
                                        {{-- ucfirst() buat bikin huruf pertama kapital (Contoh: "Plan to watch") --}}
                                        <p class="mt-1 text-xs text-slate-400">{{ ucfirst(str_replace('_', ' ', $item->status)) }}</p>
                                    </div>
                                </article>
                            @endif
                        @empty
                            <p class="col-span-full text-center text-slate-500 py-6 border border-dashed border-slate-800 rounded-xl">Watchlist kosong.</p>
                        @endforelse
                    </div>
                @else
                    {{-- Tampilan kalau user belum login --}}
                    <div class="rounded-xl border border-slate-700 bg-slate-900 p-6 text-center">
                        <p class="mb-4 text-slate-300 text-sm">Login buat kelola watchlist pribadi.</p>
                        <a href="{{ route('login') }}" class="rounded-lg bg-cyan-600 px-4 py-2 text-sm font-semibold">Login</a>
                    </div>
                @endauth
            </section>

        </div>
    </div>

    {{-- LOGIC CAROUSEL SEDERHANA --}}
    @if ($popularMovies->count() > 1)
        <script>
            // Pakai DOMContentLoaded biar script jalan setelah HTML kelar di-render
            document.addEventListener('DOMContentLoaded', function () {
                const items = document.querySelectorAll('.carousel-item');
                let current = 0;

                const showSlide = (index) => {
                    items.forEach((item, i) => {
                        item.classList.toggle('hidden', i !== index);
                        item.classList.toggle('block', i === index);
                    });
                };

                // Auto-slide setiap 5.5 detik
                setInterval(() => {
                    current = (current + 1) % items.length;
                    showSlide(current);
                }, 5500);
            });
        </script>
    @endif
</x-app-layout>