<x-app-layout>
{{--
    ============================================================
    HALAMAN DETAIL FILM
    Variabel yang tersedia dari MovieController@show:
      - $movie          : Movie model (dengan relasi: directors, actors, genres, reviews.user)
      - $userWatchlist  : Watchlist|null – watchlist item user yang sedang login
      - $userRating     : Rating|null    – rating dari user yang sedang login
      - $userReview     : Review|null    – review dari user yang sedang login
    ============================================================
--}}

{{-- ═══════════════════════════════════════════════════════════
     TRAILER MODAL (tersembunyi secara default, muncul via JS)
     ═══════════════════════════════════════════════════════════ --}}
@if ($movie->trailer_embed_url)
<div
    id="trailer-modal"
    class="fixed inset-0 z-50 hidden items-center justify-center bg-black/80 backdrop-blur-sm p-4"
    role="dialog"
    aria-modal="true"
    aria-label="Movie Trailer">

    <div class="relative w-full max-w-4xl">

        {{-- Tombol tutup modal --}}
        <button
            id="trailer-close"
            class="absolute -top-10 right-0 text-white/70 hover:text-white transition-colors flex items-center gap-1.5 text-sm font-semibold"
            aria-label="Close trailer">
            <i class="bi bi-x-lg"></i> Close
        </button>

        {{-- Embed YouTube dengan aspect ratio 16:9 --}}
        <div class="aspect-video w-full overflow-hidden rounded-2xl shadow-2xl">
            <iframe
                id="trailer-iframe"
                src=""
                data-src="{{ $movie->trailer_embed_url }}?autoplay=1&rel=0"
                title="{{ $movie->title }} – Official Trailer"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                allowfullscreen
                class="w-full h-full border-0">
            </iframe>
        </div>
    </div>
</div>
@endif

<div class="elit-page pb-24">

    {{-- ═══════════════════════════════════════════════════════
         SECTION 1: HERO BANNER + TRAILER TRIGGER
         ═══════════════════════════════════════════════════════ --}}
    <section class="relative overflow-hidden" aria-label="Movie banner">

        {{-- Banner background (gambar atau gradient fallback) --}}
        @if ($movie->banner_url)
            <img
                src="{{ $movie->banner_url }}"
                alt="{{ $movie->title }}"
                class="absolute inset-0 w-full h-full object-cover"
                aria-hidden="true">
        @else
            <div
                class="absolute inset-0 bg-[radial-gradient(circle_at_50%_10%,rgba(196,183,232,.26),transparent_22rem),linear-gradient(135deg,#6a519b_0%,#2a1945_55%,#160d26_100%)]"
                aria-hidden="true">
            </div>
        @endif

        {{-- Gradient overlay gelap agar teks terbaca --}}
        <div class="absolute inset-0 bg-gradient-to-b from-[#1b1230]/30 via-[#1b1230]/65 to-[#1b1230]" aria-hidden="true"></div>

        {{-- Konten hero --}}
        <div class="elit-shell relative">

            {{-- === Area Banner Utama (klik untuk buka trailer) === --}}
            @if ($movie->trailer_embed_url)
                <button
                    id="trailer-open"
                    class="group relative block w-full overflow-hidden rounded-b-2xl cursor-pointer focus:outline-none focus-visible:ring-2 focus-visible:ring-violet-300"
                    aria-label="Play trailer for {{ $movie->title }}"
                    title="Watch Trailer">

                    {{-- Spacer untuk tinggi banner --}}
                    <div class="aspect-[21/9] sm:aspect-[21/8] w-full"></div>

                    {{-- Overlay play button di tengah --}}
                    <div class="absolute inset-0 flex flex-col items-center justify-center gap-3 transition-all duration-300">
                        <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-full bg-white/15 border-2 border-white/40 flex items-center justify-center group-hover:bg-white/25 group-hover:scale-105 transition-all duration-300 shadow-2xl backdrop-blur-sm">
                            <i class="bi bi-play-fill text-white text-3xl sm:text-4xl ml-1"></i>
                        </div>
                        <span class="text-white/80 text-xs font-semibold tracking-widest uppercase">Watch Trailer</span>
                    </div>
                </button>
            @else
                {{-- Jika tidak ada trailer, banner hanya jadi spacer visual --}}
                <div class="aspect-[21/9] sm:aspect-[21/8] w-full"></div>
            @endif

            {{-- === Detail Film (poster + info teks) === --}}
            <div class="relative -mt-24 sm:-mt-32 pb-10 grid gap-6 md:gap-8 md:grid-cols-[13rem_minmax(0,1fr)] lg:grid-cols-[15rem_minmax(0,1fr)]">

                {{-- Kolom Kiri: Poster --}}
                <div class="hidden md:block">
                    <div class="rounded-xl overflow-hidden shadow-2xl ring-1 ring-violet-200/10">
                        @if ($movie->poster_url)
                            <img
                                src="{{ $movie->poster_url }}"
                                alt="Poster {{ $movie->title }}"
                                class="w-full object-cover aspect-[2/3]">
                        @else
                            <div class="poster-placeholder aspect-[2/3] w-full">
                                <span>{{ str($movie->title)->limit(24) }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Kolom Kanan: Info film --}}
                <div class="pt-4 sm:pt-8">

                    {{-- Judul + Tahun --}}
                    <h1 class="text-3xl sm:text-4xl font-black uppercase tracking-tight text-white leading-tight">
                        {{ $movie->title }}
                        @if ($movie->release_year)
                            <span class="text-violet-300/70 font-extrabold">({{ $movie->release_year }})</span>
                        @endif
                    </h1>

                    {{-- Meta: tahun, durasi, genre tags --}}
                    <div class="mt-3 flex flex-wrap items-center gap-2 text-sm">

                        @if ($movie->release_year)
                            <span class="text-violet-200/75 font-semibold">{{ $movie->release_year }}</span>
                            <span class="text-violet-200/30">·</span>
                        @endif

                        <span class="text-violet-200/75 font-semibold flex items-center gap-1">
                            <i class="bi bi-clock text-xs"></i>
                            {{ $movie->duration_formatted }}
                        </span>

                        @if ($movie->genres->isNotEmpty())
                            <span class="text-violet-200/30">·</span>
                            @foreach ($movie->genres as $genre)
                                <span class="rounded-full bg-violet-200/15 border border-violet-200/25 px-3 py-0.5 text-xs font-bold text-violet-200/90">
                                    {{ $genre->name }}
                                </span>
                            @endforeach
                        @endif
                    </div>

                    {{-- Sinopsis --}}
                    <p class="mt-4 max-w-2xl text-sm leading-7 text-white/75 font-medium">
                        {{ $movie->description ?: 'Belum ada deskripsi untuk film ini.' }}
                    </p>

                    {{-- Rating rata-rata --}}
                    <div class="mt-5 inline-flex items-center gap-3 rounded-xl bg-white/5 border border-white/10 px-4 py-3">
                        <span class="text-3xl font-black text-white tabular-nums">
                            {{ number_format((float) $movie->average_score, 1) }}
                        </span>
                        <div>
                            <div class="flex items-center gap-0.5 text-amber-400 text-base">
                                @foreach ($movie->average_score_star_icons as $icon)
                                    <i class="bi {{ $icon }}"></i>
                                @endforeach
                            </div>
                            <p class="text-[11px] text-violet-200/55 font-semibold mt-0.5">
                                Average Rating
                            </p>
                        </div>
                    </div>

                    {{-- Watchlist & tombol navigasi --}}
                    <div class="mt-5 flex flex-wrap items-center gap-3">

                        {{-- Watchlist (hanya untuk user yang login) --}}
                        @auth
                            <form
                                method="POST"
                                action="{{ route('watchlist.store', $movie) }}"
                                class="flex flex-wrap items-center gap-2">
                                @csrf

                                {{-- Status label --}}
                                <span class="text-xs font-semibold text-violet-200/70">
                                    {{ $userWatchlist ? '✓ Already in Watch List' : 'Add To Watch List' }}
                                </span>

                                {{-- Dropdown status --}}
                                <select
                                    name="status"
                                    class="elit-select w-auto min-w-40 px-3 py-1.5 text-xs font-black"
                                    onchange="this.form.submit()">
                                    <option value="plan_to_watch" @selected(($userWatchlist?->status ?? '') === 'plan_to_watch')>
                                        Plan To Watch
                                    </option>
                                    <option value="watching" @selected(($userWatchlist?->status ?? '') === 'watching')>
                                        Watching
                                    </option>
                                    <option value="completed" @selected(($userWatchlist?->status ?? '') === 'completed')>
                                        Completed
                                    </option>
                                </select>

                                <button type="submit" class="elit-btn py-1.5 px-3 text-xs">
                                    <i class="bi bi-check-lg mr-1"></i>Save
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="elit-ghost-btn gap-1.5">
                                <i class="bi bi-plus-lg"></i> Add To Watch List
                            </a>
                        @endauth

                        {{-- Tombol scroll to sections --}}
                        <a href="#cast" class="elit-ghost-btn gap-1.5 text-xs">
                            <i class="bi bi-people"></i> Cast
                        </a>
                        <a href="#reviews" class="elit-ghost-btn gap-1.5 text-xs">
                            <i class="bi bi-chat-square-text"></i> Reviews
                        </a>
                    </div>

                </div>{{-- /kolom kanan --}}
            </div>{{-- /grid detail --}}
        </div>{{-- /elit-shell --}}
    </section>
    {{-- ═══════════════════════════════════════════════════════ END SECTION 1 ═════ --}}


    {{-- ═══════════════════════════════════════════════════════
         MAIN CONTENT CONTAINER
         ═══════════════════════════════════════════════════════ --}}
    <div class="elit-shell mt-12 space-y-14">


        {{-- ═══════════════════════════════════════════════════
             SECTION 2: CAST & CREW (horizontal scroll)
             ═══════════════════════════════════════════════════ --}}
        <section id="cast" aria-labelledby="cast-heading">

            {{-- Section header --}}
            <div class="flex items-end justify-between mb-6">
                <h2 id="cast-heading" class="elit-section-title">Cast & Crew</h2>
                @if ($movie->actors->count() + $movie->directors->count() > 6)
                    <span class="text-xs text-violet-300/50 font-semibold">Scroll →</span>
                @endif
            </div>

            {{-- Horizontal scroll strip --}}
            <div class="flex gap-6 overflow-x-auto pb-4 scroll-smooth"
                 style="scrollbar-width: thin; scrollbar-color: rgba(196,183,232,.3) transparent;">

                {{-- Directors dahulu --}}
                @foreach ($movie->directors as $director)
                    @include('movies.partials.cast-card', [
                        'person' => $director,
                        'role'   => 'Director',
                    ])
                @endforeach

                {{-- Actors --}}
                @foreach ($movie->actors as $actor)
                    @include('movies.partials.cast-card', [
                        'person' => $actor,
                        'role'   => $actor->pivot->role_name ?: 'Cast',
                    ])
                @endforeach

                {{-- Empty state --}}
                @if ($movie->directors->isEmpty() && $movie->actors->isEmpty())
                    <p class="text-violet-200/50 text-sm font-medium py-8">
                        Belum ada data cast & crew untuk film ini.
                    </p>
                @endif

            </div>
        </section>
        {{-- ═══════════════════════════════════════════════════ END SECTION 2 ═════ --}}


        {{-- ═══════════════════════════════════════════════════
             SECTION 3: RATING & REVIEW FORM
             ═══════════════════════════════════════════════════ --}}
        <section
            id="my-rating"
            class="border-t border-violet-200/10 pt-10"
            aria-labelledby="my-rating-heading">

            <h2 id="my-rating-heading" class="elit-section-title mb-6">My Rating & Review</h2>

            <div class="grid gap-6 md:grid-cols-2 max-w-3xl">

                @auth

                    {{-- === A. Rating Picker === --}}
                    <div class="rounded-2xl border border-violet-200/15 bg-white/[.04] p-6">
                        <p class="text-xs font-bold text-violet-200/60 uppercase tracking-widest mb-3">
                            Your Rating
                        </p>

                        <form
                            id="rating-form"
                            method="POST"
                            action="{{ route('movies.ratings.store', $movie) }}">
                            @csrf

                            {{-- Star picker interaktif --}}
                            <div class="flex items-center gap-1 text-3xl" id="star-picker" role="group" aria-label="Rate this movie">
                                @for ($score = 1; $score <= 5; $score++)
                                    <label
                                        class="cursor-pointer transition-transform duration-150 hover:scale-110"
                                        for="star-{{ $score }}"
                                        title="{{ $score }} star{{ $score > 1 ? 's' : '' }}">
                                        <input
                                            type="radio"
                                            id="star-{{ $score }}"
                                            name="score"
                                            value="{{ $score }}"
                                            class="sr-only"
                                            @checked((int)($userRating?->score ?? 0) === $score)
                                            onchange="this.form.submit()">
                                        <i class="bi {{ (int)($userRating?->score ?? 0) >= $score ? 'bi-star-fill text-amber-400' : 'bi-star text-violet-300/40' }} select-none pointer-events-none"></i>
                                    </label>
                                @endfor
                            </div>

                            @if ($userRating)
                                <p class="mt-2 text-xs text-violet-200/50 font-medium">
                                    Your current rating: <strong class="text-violet-200">{{ $userRating->score }}/5</strong>
                                </p>
                            @else
                                <p class="mt-2 text-xs text-violet-200/40 font-medium">
                                    Click a star to rate this film.
                                </p>
                            @endif
                        </form>
                    </div>

                    {{-- === B. Review Form === --}}
                    <div class="rounded-2xl border border-violet-200/15 bg-white/[.04] p-6">
                        <p class="text-xs font-bold text-violet-200/60 uppercase tracking-widest mb-3">
                            Your Review
                        </p>

                        <form
                            method="POST"
                            action="{{ route('movies.reviews.store', $movie) }}">
                            @csrf

                            <textarea
                                name="review_text"
                                rows="4"
                                class="elit-textarea w-full px-4 py-3 text-sm resize-none"
                                placeholder="What did you think of {{ $movie->title }}? Share your thoughts…">{{ old('review_text', $userReview?->review_text ?? '') }}</textarea>

                            <div class="mt-3 flex justify-end">
                                <button type="submit" class="elit-btn gap-2">
                                    <i class="bi bi-send"></i>
                                    {{ $userReview ? 'Update Review' : 'Post Review' }}
                                </button>
                            </div>
                        </form>
                    </div>

                @else

                    {{-- === Kondisi belum login === --}}
                    <div class="col-span-2 rounded-2xl border border-dashed border-violet-200/25 bg-white/[.03] p-8 text-center">
                        <div class="inline-flex items-center justify-center w-14 h-14 rounded-full bg-violet-300/10 mb-4">
                            <i class="bi bi-lock text-2xl text-violet-300/60"></i>
                        </div>
                        <h3 class="text-base font-black text-white mb-1">Login to Rate & Review</h3>
                        <p class="text-sm text-violet-200/60 font-medium mb-5">
                            Silakan login untuk memberikan rating dan menulis review untuk film ini.
                        </p>
                        <a href="{{ route('login') }}" class="elit-btn gap-2 px-6">
                            <i class="bi bi-box-arrow-in-right"></i> Login Sekarang
                        </a>
                    </div>

                @endauth

            </div>
        </section>
        {{-- ══════════════════════════════════════════════════ END SECTION 3 ══════ --}}


        {{-- ═══════════════════════════════════════════════════
             SECTION 4: DAFTAR REVIEW
             ═══════════════════════════════════════════════════ --}}
        <section id="reviews" class="border-t border-violet-200/10 pt-10" aria-labelledby="reviews-heading">

            {{-- Section header --}}
            <div class="flex flex-wrap items-end justify-between gap-3 mb-6">
                <h2 id="reviews-heading" class="elit-section-title">
                    Reviews
                    @if ($movie->reviews->isNotEmpty())
                        <span class="ml-2 text-lg text-violet-300/50 font-semibold">
                            ({{ $movie->reviews->count() }})
                        </span>
                    @endif
                </h2>
            </div>

            {{-- Daftar review --}}
            <div class="space-y-4">

                @forelse ($movie->reviews as $review)
                    @include('movies.partials.review-item', ['review' => $review])
                @empty
                    {{-- Empty state --}}
                    <div class="rounded-2xl border border-dashed border-violet-200/20 bg-white/[.02] p-10 text-center">
                        <div class="inline-flex items-center justify-center w-14 h-14 rounded-full bg-violet-300/10 mb-4">
                            <i class="bi bi-chat-square text-2xl text-violet-300/40"></i>
                        </div>
                        <p class="text-sm font-semibold text-violet-200/55">
                            Belum ada review untuk film ini. Jadilah yang pertama!
                        </p>
                    </div>
                @endforelse

            </div>

            {{-- ═══════════════════════════════════════════════
                 PAGINATION LINKS
                 Catatan: jika Anda ingin pagination fungsional, ubah controller
                 agar reviews di-paginate, lalu ganti $movie->reviews dengan $reviews
                 dan panggil {{ $reviews->links() }} di sini.
                 Saat ini reviews di-load sebagai collection langsung dari relasi.
                 ═══════════════════════════════════════════════
            --}}
            {{--
            @if ($reviews instanceof \Illuminate\Pagination\LengthAwarePaginator && $reviews->hasPages())
                <div class="mt-8 flex justify-center">
                    {{ $reviews->links() }}
                </div>
            @endif
            --}}

        </section>
        {{-- ══════════════════════════════════════════════════ END SECTION 4 ══════ --}}


    </div>{{-- /elit-shell --}}
</div>{{-- /elit-page --}}


{{-- ═══════════════════════════════════════════════════════
     JAVASCRIPT: Trailer Modal & Star Picker Hover Effect
     ═══════════════════════════════════════════════════════ --}}
@if ($movie->trailer_embed_url)
<script>
(function () {
    const modal       = document.getElementById('trailer-modal');
    const openBtn     = document.getElementById('trailer-open');
    const closeBtn    = document.getElementById('trailer-close');
    const iframe      = document.getElementById('trailer-iframe');
    const srcData     = iframe?.dataset.src;

    function openModal() {
        if (!modal) return;
        // Set src untuk autoplay
        if (iframe && srcData) iframe.src = srcData;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        if (!modal) return;
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        // Hentikan video dengan mengosongkan src
        if (iframe) iframe.src = '';
        document.body.style.overflow = '';
    }

    openBtn?.addEventListener('click', openModal);
    closeBtn?.addEventListener('click', closeModal);

    // Tutup saat klik di luar konten modal
    modal?.addEventListener('click', function (e) {
        if (e.target === modal) closeModal();
    });

    // Tutup dengan Escape
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeModal();
    });
})();
</script>
@endif

<script>
/**
 * Star Picker – efek hover interaktif.
 * Saat user hover pada bintang, bintang sebelumnya ikut menyala.
 */
(function () {
    const picker = document.getElementById('star-picker');
    if (!picker) return;

    const labels = picker.querySelectorAll('label');
    const icons  = picker.querySelectorAll('i');

    function highlightUpTo(index) {
        icons.forEach((icon, i) => {
            if (i <= index) {
                icon.className = 'bi bi-star-fill text-amber-400 select-none pointer-events-none';
            } else {
                icon.className = 'bi bi-star text-violet-300/40 select-none pointer-events-none';
            }
        });
    }

    function resetToChecked() {
        const checked = picker.querySelector('input[type="radio"]:checked');
        const checkedVal = checked ? parseInt(checked.value, 10) - 1 : -1;
        icons.forEach((icon, i) => {
            if (i <= checkedVal) {
                icon.className = 'bi bi-star-fill text-amber-400 select-none pointer-events-none';
            } else {
                icon.className = 'bi bi-star text-violet-300/40 select-none pointer-events-none';
            }
        });
    }

    labels.forEach((label, index) => {
        label.addEventListener('mouseenter', () => highlightUpTo(index));
        label.addEventListener('mouseleave', resetToChecked);
    });
})();
</script>

</x-app-layout>