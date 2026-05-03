<x-app-layout>
    <div class="bg-[#1c1527] text-white">
        <section class="relative overflow-hidden border-b border-white/5 pt-28 pb-16 sm:pt-32" id="heroCarousel">
            @if ($heroMovies->isNotEmpty())
                <img
                    id="heroBackground"
                    src="{{ $heroMovies->first()->banner_url ?: $heroMovies->first()->poster_url }}"
                    alt="Hero banner"
                    class="absolute inset-0 h-full w-full object-cover object-top opacity-100 brightness-[.55] saturate-[1.2] transition-opacity duration-500"
                >
            @endif
            <div class="absolute inset-0 bg-[linear-gradient(135deg,rgba(18,17,42,.88)_0%,rgba(18,17,42,.48)_58%,rgba(28,21,39,.82)_100%)]"></div>
            <div class="absolute inset-x-0 bottom-0 h-40 bg-gradient-to-b from-transparent to-[#1c1527]"></div>

            <div class="relative z-10 mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <h1 class="text-center text-sm font-extrabold uppercase tracking-[0.22em] text-white sm:text-base">
                    Top 5 Movies of {{ $monthName }}
                </h1>

                @if ($heroMovies->isNotEmpty())
                    <div class="relative mt-8 overflow-hidden rounded-xl">
                        <div id="movieCarouselTrack" class="flex transition-transform duration-700 ease-in-out">
                            @foreach ($heroMovies as $index => $movie)
                                @php
                                    $heroScore = (float) ($movie->month_avg_score ?? $movie->average_score ?? 0);
                                    $heroRatingCount = (int) ($movie->month_ratings_count ?? $movie->ratings_count ?? 0);
                                @endphp
                                <article class="min-w-full px-1 py-4" data-hero-slide data-banner-url="{{ $movie->banner_url ?: $movie->poster_url }}">
                                    <div class="grid items-center gap-8 md:grid-cols-[220px_1fr_120px] md:gap-10 lg:grid-cols-[260px_1fr_150px]">
                                        <div class="mx-auto w-40 md:mx-0 md:ml-auto md:w-56">
                                            <a href="{{ route('movies.show', $movie) }}" class="block overflow-hidden rounded-xl shadow-[0_22px_55px_rgba(0,0,0,.58)] ring-1 ring-white/10">
                                                <img src="{{ $movie->poster_url ?: asset('images/default-poster.svg') }}" alt="{{ $movie->title }} poster" class="h-60 w-full object-cover sm:h-72 md:h-[350px]">
                                            </a>
                                        </div>

                                        <div class="text-center md:text-left">
                                            <p class="text-xs font-bold uppercase tracking-[0.28em] text-[#f1c40f]">Featured #{{ $index + 1 }}</p>
                                            <h2 class="mt-2 text-3xl font-black uppercase leading-tight tracking-wide text-white sm:text-5xl">
                                                {{ $movie->title }}
                                            </h2>
                                            <p class="mt-4 max-w-2xl text-sm leading-7 text-[#c9c2d8] md:text-base">
                                                {{ \Illuminate\Support\Str::limit($movie->description ?: 'Belum ada deskripsi film.', 170) }}
                                            </p>

                                            <div class="mt-5 flex flex-wrap items-center justify-center gap-4 md:justify-start">
                                                @if ($heroScore > 0)
                                                    <span class="text-5xl font-black leading-none text-white">{{ number_format($heroScore, 1) }}</span>
                                                    <span class="flex flex-col gap-1 text-left">
                                                        <span class="text-xl text-[#f1c40f]">
                                                            @foreach ($movie->average_score_star_icons as $icon)
                                                                <i class="bi {{ $icon }}"></i>
                                                            @endforeach
                                                        </span>
                                                        <span class="text-xs text-[#a9a2b8]">{{ $heroRatingCount }} rating{{ $heroRatingCount === 1 ? '' : 's' }}</span>
                                                    </span>
                                                @else
                                                    <span class="text-sm text-[#a9a2b8]">No ratings yet</span>
                                                @endif
                                            </div>

                                            <a href="{{ route('movies.show', $movie) }}" class="mt-7 inline-flex items-center gap-2 rounded-lg bg-[#f1c40f] px-5 py-3 text-sm font-extrabold text-black transition hover:-translate-y-0.5 hover:bg-[#ffd84d]">
                                                <i class="bi bi-play-fill text-lg"></i>
                                                View Details
                                            </a>
                                        </div>

                                        <div class="hidden text-center md:block">
                                            <div class="text-7xl font-black text-white/65 lg:text-8xl">#{{ $index + 1 }}</div>
                                        </div>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    </div>

                    <div class="mt-6 flex items-center justify-center gap-5">
                        <button type="button" data-hero-prev class="flex h-11 w-11 items-center justify-center rounded-full border border-white/30 text-white transition hover:bg-white hover:text-black" aria-label="Previous movie">
                            <i class="bi bi-chevron-left"></i>
                        </button>
                        <div id="carouselDots" class="flex items-center gap-2">
                            @foreach ($heroMovies as $index => $movie)
                                <button type="button" data-hero-dot="{{ $index }}" class="h-3 w-3 rounded-full transition {{ $index === 0 ? 'bg-[#f1c40f]' : 'bg-white/40' }}" aria-label="Go to movie {{ $index + 1 }}"></button>
                            @endforeach
                        </div>
                        <button type="button" data-hero-next class="flex h-11 w-11 items-center justify-center rounded-full border border-white/30 text-white transition hover:bg-white hover:text-black" aria-label="Next movie">
                            <i class="bi bi-chevron-right"></i>
                        </button>
                    </div>
                @else
                    <div class="mt-12 rounded-2xl border border-white/10 bg-white/5 p-8 text-center text-[#a9a2b8]">
                        Belum ada movie untuk ditampilkan.
                    </div>
                @endif
            </div>
        </section>

        <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-6 rounded-xl border border-emerald-400/30 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-100">
                    {{ session('success') }}
                </div>
            @endif

            <section>
                <div class="mb-5 flex items-center justify-between border-b border-white/10 pb-3">
                    <h2 class="text-xl font-extrabold tracking-wide text-white">Movie List</h2>
                    @if ($hasMoreRecommended)
                        <a href="{{ route('search') }}" class="text-sm font-semibold text-[#b39ddb] hover:text-[#f1c40f]">See More <i class="bi bi-arrow-right"></i></a>
                    @endif
                </div>

                <div class="relative" data-scroll-section>
                    <div data-scroll-container class="flex gap-4 overflow-x-auto pb-4 [scrollbar-width:none] [&::-webkit-scrollbar]:hidden">
                        @forelse ($recommendedMovies as $movie)
                        <x-movie-card :movie="$movie" :watchlist-status="$movie->watchlists->first()?->status"/>
                        @empty
                        <p class="text-[#a9a2b8]">No movies found.</p>
                        @endforelse
                    </div>
                </div>
            </section>

            @auth
                <section class="mt-12">
                    <div class="mb-5 flex items-center justify-between border-b border-white/10 pb-3">
                        <h2 class="text-xl font-extrabold tracking-wide text-white">My Watch List</h2>
                        @if ($hasMoreWatchlist)
                            <a href="{{ route('watchlist.index') }}" class="text-sm font-semibold text-[#b39ddb] hover:text-[#f1c40f]">See More <i class="bi bi-arrow-right"></i></a>
                        @endif
                    </div>

                    <div class="relative" data-scroll-section>
                        <div data-scroll-container class="flex gap-4 overflow-x-auto pb-4 [scrollbar-width:none] [&::-webkit-scrollbar]:hidden">
                            @forelse ($userWatchlist as $item)
                                @if ($item->movie)
                                    <x-movie-card :movie="$item->movie" :watchlist-status="$item->status" :show-watchlist="true" />
                                @endif
                            @empty
                                <div class="min-w-full rounded-xl border border-dashed border-white/10 bg-white/5 p-8 text-center text-[#a9a2b8]">
                                    Watchlist kamu masih kosong.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </section>
            @endauth

            <section class="mt-12">
                <div class="mb-5 flex items-center justify-between border-b border-white/10 pb-3">
                    <h2 class="text-xl font-extrabold lowercase tracking-wide text-white">other reviews</h2>
                </div>

                <div class="relative" data-scroll-section>

                    <div data-scroll-container class="flex gap-4 overflow-x-auto pb-4 [scrollbar-width:none] [&::-webkit-scrollbar]:hidden">
                        @forelse ($latestReviews as $review)
                            <article class="min-w-[285px] max-w-[285px] rounded-xl border border-[#7a669f]/25 bg-[#2f2543] p-4 shadow-[0_14px_30px_rgba(0,0,0,.2)] sm:min-w-[330px] sm:max-w-[330px]">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-10 w-10 shrink-0 items-center justify-center overflow-hidden rounded-full border-2 border-[#7a669f] bg-[#312a4a] text-sm font-extrabold uppercase text-[#d6c6ff]">
                                            @if ($review->user?->profile_photo_url)
                                                <img src="{{ $review->user->profile_photo_url }}" alt="{{ $review->user->username }}" class="h-full w-full object-cover">
                                            @else
                                                {{ mb_strtoupper(mb_substr($review->user->username ?? $review->user->name ?? 'U', 0, 1)) }}
                                            @endif
                                        </div>
                                        <div>
                                            <div class="text-sm font-bold text-white">{{ $review->user->username ?? $review->user->name }}</div>
                                            <div class="text-xs text-[#a9a2b8]">{{ optional($review->created_at)->format('n/j/Y') }}</div>
                                        </div>
                                    </div>
                                    <div class="text-xs font-semibold text-[#f1c40f]">
                                        <i class="bi bi-heart"></i> {{ $review->likes_count }}
                                    </div>
                                </div>

                                <p class="mt-4 line-clamp-4 text-sm leading-6 text-[#d1cde0]">{{ $review->review_text }}</p>
                                @if ($review->movie)
                                    <a href="{{ route('movies.show', $review->movie) }}" class="mt-4 inline-flex text-xs font-semibold text-[#b39ddb] hover:text-[#f1c40f]">
                                        <i class="bi bi-chat-right-text mr-1"></i>{{ $review->movie->title }}
                                    </a>
                                @endif
                            </article>
                        @empty
                            <p class="text-[#a9a2b8]">No reviews yet.</p>
                        @endforelse
                    </div>
                </div>
            </section>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const track = document.getElementById('movieCarouselTrack');
                const bg = document.getElementById('heroBackground');
                const slides = document.querySelectorAll('[data-hero-slide]');
                const dots = document.querySelectorAll('[data-hero-dot]');
                const totalSlides = slides.length;
                let currentSlide = 0;
                let autoPlay;

                function showHeroSlide(index) {
                    if (!track || totalSlides === 0) return;

                    currentSlide = (index + totalSlides) % totalSlides;
                    track.style.transform = `translateX(-${currentSlide * 100}%)`;

                    if (bg && slides[currentSlide]?.dataset.bannerUrl) {
                        bg.style.opacity = '0.25';
                        window.setTimeout(function () {
                            bg.src = slides[currentSlide].dataset.bannerUrl;
                            bg.style.opacity = '1';
                        }, 250);
                    }

                    dots.forEach(function (dot, i) {
                        dot.classList.toggle('bg-[#f1c40f]', i === currentSlide);
                        dot.classList.toggle('bg-white/40', i !== currentSlide);
                        dot.classList.toggle('w-8', i === currentSlide);
                    });
                }

                function startAutoPlay() {
                    if (totalSlides <= 1) return;
                    autoPlay = window.setInterval(function () {
                        showHeroSlide(currentSlide + 1);
                    }, 6500);
                }

                document.querySelector('[data-hero-prev]')?.addEventListener('click', function () {
                    window.clearInterval(autoPlay);
                    showHeroSlide(currentSlide - 1);
                    startAutoPlay();
                });

                document.querySelector('[data-hero-next]')?.addEventListener('click', function () {
                    window.clearInterval(autoPlay);
                    showHeroSlide(currentSlide + 1);
                    startAutoPlay();
                });

                dots.forEach(function (dot) {
                    dot.addEventListener('click', function () {
                        window.clearInterval(autoPlay);
                        showHeroSlide(parseInt(dot.dataset.heroDot, 10));
                        startAutoPlay();
                    });
                });

                startAutoPlay();

                document.querySelectorAll('[data-scroll-section]').forEach(function (section) {
                    const container = section.querySelector('[data-scroll-container]');
                    const next = section.querySelector('[data-scroll-next]');

                    next?.addEventListener('click', function () {
                        container?.scrollBy({ left: 520, behavior: 'smooth' });
                    });
                });
            });
        </script>
    @endpush
</x-app-layout>
