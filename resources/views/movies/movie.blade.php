<x-app-layout>
    @php
        $banner = $movie->banner_url ?: $movie->poster_url ?: asset('images/default-banner.svg');
        $poster = $movie->poster_url ?: asset('images/default-poster.svg');
        $averageScore = (float) $movie->average_score;
        $ratingCount = (int) ($movie->ratings_count ?? 0);
        $selectedStatus = old('status', $userWatchlist?->status ?? 'plan_to_watch');
        $currentUserScore = (int) old('score', $userRating?->score ?? 0);
        $trailerEmbedUrl = $movie->trailer_embed_url;
    @endphp

    <div class="min-h-screen bg-[#1c1527] text-white">
        @if (session('success'))
            <div class="mx-auto max-w-5xl px-4 pt-6 sm:px-6 lg:px-8">
                <div class="rounded-xl border border-emerald-400/30 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-100">
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if ($errors->any())
            <div class="mx-auto max-w-5xl px-4 pt-6 sm:px-6 lg:px-8">
                <div class="rounded-xl border border-red-400/30 bg-red-500/10 px-4 py-3 text-sm text-red-100">
                    <div class="font-bold">Ada input yang perlu diperbaiki:</div>
                    <ul class="mt-2 list-disc space-y-1 pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <section class="relative min-h-[420px] overflow-hidden sm:min-h-[500px]">
            <img src="{{ $banner }}" alt="{{ $movie->title }} banner" class="absolute inset-0 h-full w-full object-cover object-top brightness-[.55] saturate-[1.18]">
            <div class="absolute inset-0 bg-[linear-gradient(135deg,rgba(18,17,42,.84)_0%,rgba(18,17,42,.34)_50%,rgba(28,21,39,.82)_100%)]"></div>
            <div class="absolute inset-x-0 bottom-0 h-56 bg-gradient-to-b from-transparent via-[#1c1527]/80 to-[#1c1527]"></div>

            @if ($trailerEmbedUrl)
                <button
                    type="button"
                    data-open-trailer
                    data-trailer-src="{{ $trailerEmbedUrl }}"
                    class="absolute left-1/2 top-1/2 z-10 flex h-20 w-20 -translate-x-1/2 -translate-y-1/2 items-center justify-center rounded-full border border-white/40 bg-white/15 text-white shadow-[0_0_60px_rgba(255,255,255,.16)] backdrop-blur-md transition hover:scale-110 hover:bg-white/25 focus:outline-none focus:ring-4 focus:ring-white/30 sm:h-24 sm:w-24"
                    aria-label="Play trailer {{ $movie->title }}"
                >
                    <i class="bi bi-play-fill translate-x-0.5 text-5xl sm:text-6xl"></i>
                </button>
            @endif
        </section>

        <section class="relative z-10 -mt-28 pb-10">
            <div class="mx-auto flex max-w-5xl flex-col gap-6 px-4 sm:px-6 md:flex-row md:items-start lg:px-8">
                <div class="mx-auto w-36 shrink-0 md:mx-0 md:w-[130px]">
                    <img src="{{ $poster }}" alt="{{ $movie->title }} poster" class="h-52 w-full rounded-xl border-2 border-[#7a669f]/25 object-cover shadow-[0_18px_45px_rgba(0,0,0,.75)] md:h-[190px]">
                </div>

                <div class="min-w-0 flex-1 rounded-2xl border border-white/5 bg-[#1c1527]/72 p-5 shadow-[0_18px_55px_rgba(0,0,0,.24)] backdrop-blur-sm md:bg-transparent md:p-0 md:shadow-none md:backdrop-blur-0">
                    <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
                        <div>
                            <a href="{{ url()->previous() }}" class="mb-3 inline-flex items-center gap-2 text-xs font-semibold uppercase tracking-wide text-[#b39ddb] hover:text-[#f1c40f]">
                                <i class="bi bi-arrow-left"></i> Back
                            </a>
                            <h1 class="text-3xl font-black uppercase leading-tight tracking-wide text-white sm:text-4xl">
                                {{ $movie->title }}
                                @if ($movie->release_year)
                                    <span class="text-xl font-normal text-white/60 sm:text-2xl">({{ $movie->release_year }})</span>
                                @endif
                            </h1>

                            <div class="mt-3 flex flex-wrap gap-2 text-xs text-[#a9a2b8]">
                                @if ($movie->release_year)
                                    <span>{{ $movie->release_year }}</span>
                                @endif
                                @if ($movie->duration_minutes)
                                    <span>&middot;</span>
                                    <span>Duration : {{ $movie->duration_formatted }}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if ($movie->directors->isNotEmpty())
                        <div class="mt-4 text-sm text-[#a9a2b8]">
                            <span class="font-semibold text-white/85">Director{{ $movie->directors->count() > 1 ? 's' : '' }}:</span>
                            {{ $movie->directors->pluck('name')->join(', ') }}
                        </div>
                    @endif

                    @if ($movie->genres->isNotEmpty())
                        <div class="mt-4 flex flex-wrap gap-2">
                            @foreach ($movie->genres as $genre)
                                <span class="rounded-full border border-[#7a669f] bg-[#7a669f]/25 px-3 py-1 text-[0.68rem] font-extrabold uppercase tracking-wider text-[#d6c6ff]">
                                    {{ $genre->name }}
                                </span>
                            @endforeach
                        </div>
                    @endif

                    <p class="mt-5 max-w-3xl text-sm leading-7 text-[#c9c2d8]">
                        {{ $movie->description ?: 'Belum ada deskripsi film.' }}
                    </p>

                    <div class="mt-6 flex flex-wrap items-center justify-between gap-4">
                        <div>
                            @if ($averageScore > 0)
                                <div class="flex items-center gap-3">
                                    <span class="text-4xl font-black leading-none text-white">{{ number_format($averageScore, 1) }}</span>
                                    <span class="flex flex-col gap-1">
                                        <span class="text-sm text-[#f1c40f]">
                                            @foreach ($movie->average_score_star_icons as $icon)
                                                <i class="bi {{ $icon }}"></i>
                                            @endforeach
                                        </span>
                                        <span class="text-xs text-[#a9a2b8]">{{ $ratingCount }} rating{{ $ratingCount === 1 ? '' : 's' }}</span>
                                    </span>
                                </div>
                            @else
                                <span class="text-sm text-[#a9a2b8]">No ratings yet</span>
                            @endif
                        </div>

                        @auth
                            <div class="flex flex-wrap items-center gap-2">
                                <form method="POST" action="{{ route('watchlist.store', $movie) }}" class="flex flex-wrap items-center gap-2">
                                    @csrf
                                    <select name="status" class="rounded-lg border border-[#7a669f] bg-[#312a4a] px-3 py-2 text-sm text-white focus:border-[#a855f7] focus:outline-none focus:ring-[#a855f7]">
                                        <option value="plan_to_watch" @selected($selectedStatus === 'plan_to_watch')>Plan to Watch</option>
                                        <option value="watching" @selected($selectedStatus === 'watching')>Watching</option>
                                        <option value="completed" @selected($selectedStatus === 'completed')>Completed</option>
                                    </select>
                                    <button type="submit" class="inline-flex items-center gap-2 rounded-lg border border-[#7a669f] px-4 py-2 text-sm font-bold text-[#d6c6ff] transition hover:bg-[#7a669f] hover:text-white">
                                        <i class="bi {{ $userWatchlist ? 'bi-bookmark-fill' : 'bi-bookmark-plus' }}"></i>
                                        {{ $userWatchlist ? 'Update Watchlist' : 'Add To Watch List' }}
                                    </button>
                                </form>

                                @if($userWatchlist)
                                    <form method="POST" action="{{ route('watchlist.destroy', $movie) }}" onsubmit="return confirm('Hapus dari watchlist?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center gap-2 rounded-lg border border-red-300/40 px-4 py-2 text-sm font-bold text-red-100 transition hover:bg-red-500/20">
                                            <i class="bi bi-bookmark-x"></i>
                                            Remove
                                        </button>
                                    </form>
                                @endif
                            </div>
                        @else
                            <a href="{{ route('login') }}" class="inline-flex items-center gap-2 rounded-lg border border-[#7a669f] px-4 py-2 text-sm font-bold text-[#d6c6ff] transition hover:bg-[#7a669f] hover:text-white">
                                Add To Watch List <i class="bi bi-plus-lg"></i>
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </section>

        @if ($movie->actors->isNotEmpty() || $movie->directors->isNotEmpty())
            <section class="mx-auto mb-10 max-w-5xl px-4 sm:px-6 lg:px-8">
                <h2 class="mb-4 flex items-center gap-2 border-b border-[#7a669f]/25 pb-3 text-base font-black tracking-wide text-white">
                    <i class="bi bi-people-fill"></i> Cast &amp; Crew
                </h2>

                <div class="flex items-center gap-3">
                    <div id="castScrollRow" class="flex flex-1 gap-3 overflow-x-auto pb-2 [scrollbar-width:none] [&::-webkit-scrollbar]:hidden">
                        @foreach ($movie->directors as $director)
                            <article class="min-w-[100px] max-w-[100px] overflow-hidden rounded-xl border border-[#7a669f]/25 bg-[#2f2543]">
                                @if ($director->photo_url)
                                    <img src="{{ $director->photo_url }}" alt="{{ $director->name }}" class="h-[110px] w-full object-cover">
                                @else
                                    <div class="flex h-[110px] w-full items-center justify-center bg-[#312a4a] text-2xl font-black text-[#d6c6ff]">
                                        {{ mb_strtoupper(mb_substr($director->name, 0, 2)) }}
                                    </div>
                                @endif
                                <div class="p-2">
                                    <div class="text-xs font-extrabold leading-snug text-white">{{ $director->name }}</div>
                                    <div class="mt-1 text-[0.68rem] text-[#a9a2b8]">Director</div>
                                </div>
                            </article>
                        @endforeach

                        @foreach ($movie->actors as $actor)
                            <article class="min-w-[100px] max-w-[100px] overflow-hidden rounded-xl border border-[#7a669f]/25 bg-[#2f2543]">
                                @if ($actor->photo_url)
                                    <img src="{{ $actor->photo_url }}" alt="{{ $actor->name }}" class="h-[110px] w-full object-cover">
                                @else
                                    <div class="flex h-[110px] w-full items-center justify-center bg-[#312a4a] text-2xl font-black text-[#d6c6ff]">
                                        {{ mb_strtoupper(mb_substr($actor->name, 0, 2)) }}
                                    </div>
                                @endif
                                <div class="p-2">
                                    <div class="text-xs font-extrabold leading-snug text-white">{{ $actor->name }}</div>
                                    @if ($actor->pivot?->role_name)
                                        <div class="mt-1 text-[0.68rem] text-[#a9a2b8]">{{ $actor->pivot->role_name }}</div>
                                    @endif
                                </div>
                            </article>
                        @endforeach
                    </div>
                    <button type="button" data-cast-next class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full border border-[#7a669f]/25 bg-[#312a4a] text-white transition hover:bg-[#7a669f]" aria-label="Scroll cast">
                        <i class="bi bi-chevron-right"></i>
                    </button>
                </div>
            </section>
        @endif

        <section class="mx-auto mb-10 max-w-5xl px-4 sm:px-6 lg:px-8">
            <h2 class="mb-4 flex items-center gap-2 border-b border-[#7a669f]/25 pb-3 text-base font-black tracking-wide text-white">
                <i class="bi bi-chat-right-text"></i>
                Reviews
                <span class="text-sm font-normal text-[#a9a2b8]">({{ $movie->reviews->count() }})</span>
            </h2>

            @forelse ($movie->reviews as $index => $review)
                <article class="mb-4 rounded-2xl border border-[#7a669f]/25 bg-[#2f2543] p-4 shadow-[0_14px_30px_rgba(0,0,0,.18)]" style="animation-delay: {{ $index * 0.07 }}s">
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
                                <div class="text-xs text-[#a9a2b8]">{{ optional($review->created_at)->format('j/n/Y') }}</div>
                            </div>
                        </div>
                        @php
                            $likedByCurrentUser = (bool) ($review->liked_by_current_user ?? false);
                        @endphp

                        @auth
                            <form method="POST" action="{{ route('reviews.like.toggle', $review) }}">
                                @csrf
                                <button
                                    type="submit"
                                    class="rounded-full px-3 py-1 text-xs font-semibold transition {{ $likedByCurrentUser ? 'bg-pink-500/20 text-pink-200' : 'text-[#a9a2b8] hover:bg-white/10 hover:text-white' }}"
                                    aria-label="{{ $likedByCurrentUser ? 'Unlike review' : 'Like review' }}"
                                >
                                    <i class="bi {{ $likedByCurrentUser ? 'bi-heart-fill' : 'bi-heart' }}"></i>
                                    {{ $review->likes_count ?? 0 }}
                                </button>
                            </form>
                        @else
                            <a
                                href="{{ route('login') }}"
                                class="rounded-full px-3 py-1 text-xs font-semibold text-[#a9a2b8] hover:bg-white/10 hover:text-white"
                                aria-label="Login untuk like review"
                            >
                                <i class="bi bi-heart"></i> {{ $review->likes_count ?? 0 }}
                            </a>
                        @endauth
                    </div>

                    <p class="mt-4 text-sm leading-7 text-[#d1cde0]">{!! nl2br(e($review->review_text)) !!}</p>
                </article>
            @empty
                <p class="text-sm text-[#a9a2b8]">No reviews yet. Be the first!</p>
            @endforelse
        </section>

        <section class="border-t border-[#7a669f]/25 bg-[#312a4a]/45 px-4 py-10 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-5xl">
                @auth
                    <div class="text-xs font-black uppercase tracking-[0.2em] text-[#a855f7]">My Rating</div>
                    <h2 class="mt-1 text-2xl font-black uppercase text-white">
                        {{ $movie->title }} @if ($movie->release_year) ({{ $movie->release_year }}) @endif
                    </h2>
                    <p class="mt-2 text-sm font-semibold text-white">What did you think of it?</p>
                    <p class="mt-1 text-xs text-[#a9a2b8]">Pick a star rating and leave a comment</p>

                    <div class="mt-5 rounded-2xl border border-[#7a669f]/25 bg-[#2f2543] p-5">
                        <form method="POST" action="{{ route('movies.ratings.store', $movie) }}" class="flex flex-wrap items-center gap-4">
                            @csrf
                            <span class="text-sm text-[#a9a2b8]">Your rating:</span>
                            <div class="flex items-center gap-1" data-star-rating data-current="{{ $currentUserScore }}">
                                @for ($score = 1; $score <= 5; $score++)
                                    <button type="button" data-star-value="{{ $score }}" class="text-3xl transition hover:scale-110 {{ $currentUserScore >= $score ? 'text-[#f1c40f]' : 'text-[#4a4060]' }}" aria-label="Rate {{ $score }} stars">
                                        <i class="bi {{ $currentUserScore >= $score ? 'bi-star-fill' : 'bi-star' }}"></i>
                                    </button>
                                @endfor
                                <input type="hidden" name="score" id="scoreInput" value="{{ $currentUserScore ?: '' }}" required>
                            </div>
                            <button type="submit" class="rounded-lg bg-[#7a669f] px-5 py-2 text-sm font-bold text-white transition hover:scale-105 hover:bg-[#a855f7]">
                                {{ $userRating ? 'Update Rating' : 'Rate' }}
                            </button>
                        </form>
                    </div>

                    <div class="mt-4 rounded-2xl border border-[#7a669f]/25 bg-[#2f2543] p-5">
                        <form method="POST" action="{{ route('movies.reviews.store', $movie) }}">
                            @csrf
                            <textarea name="review_text" rows="4" required placeholder="Leave a comment..." class="w-full resize-none rounded-xl border border-transparent bg-transparent px-0 text-sm leading-7 text-white placeholder:text-[#a9a2b8] focus:border-transparent focus:outline-none focus:ring-0">{{ old('review_text', $userReview?->review_text ?? '') }}</textarea>
                            <div class="mt-4 flex flex-wrap items-center justify-between gap-3 border-t border-[#7a669f]/25 pt-4">
                                <span class="text-xs text-[#a9a2b8]">{{ $userReview ? 'Editing your review' : 'Writing a new review' }}</span>
                                <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-[#7a669f] px-5 py-2 text-sm font-bold text-white transition hover:scale-105 hover:bg-[#a855f7]">
                                    <i class="bi bi-send-fill"></i>
                                    {{ $userReview ? 'Update Review' : 'Post Review' }}
                                </button>
                            </div>
                        </form>
                    </div>
                @else
                    <div class="rounded-2xl border border-[#7a669f]/25 bg-[#2f2543] p-5 text-sm text-[#a9a2b8]">
                        <i class="bi bi-star-fill mr-2 text-[#f1c40f]"></i>
                        <a href="{{ route('login') }}" class="font-bold text-[#d6c6ff] hover:text-[#f1c40f]">Log in</a>
                        to rate, review, or add this movie to your watchlist.
                    </div>
                @endauth
            </div>
        </section>

        @if ($trailerEmbedUrl)
            <div id="trailerModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/80 p-4 backdrop-blur-sm" role="dialog" aria-modal="true" aria-label="Trailer modal">
                <div class="relative w-full max-w-4xl overflow-hidden rounded-2xl border border-white/10 bg-[#100b18] shadow-2xl">
                    <div class="flex items-center justify-between border-b border-white/10 px-4 py-3">
                        <h2 class="text-sm font-bold uppercase tracking-wide text-white">Trailer {{ $movie->title }}</h2>
                        <button type="button" data-close-trailer class="flex h-9 w-9 items-center justify-center rounded-full bg-white/10 text-white transition hover:bg-white/20" aria-label="Close trailer modal">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>
                    <div class="aspect-video w-full bg-black">
                        <iframe
                            data-trailer-frame
                            class="h-full w-full"
                            src=""
                            title="Trailer {{ $movie->title }}"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                            allowfullscreen
                        ></iframe>
                    </div>
                </div>
            </div>
        @endif
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const castNext = document.querySelector('[data-cast-next]');
                const castRow = document.getElementById('castScrollRow');

                castNext?.addEventListener('click', function () {
                    castRow?.scrollBy({ left: 220, behavior: 'smooth' });
                });

                const ratingWrap = document.querySelector('[data-star-rating]');
                if (ratingWrap) {
                    const stars = ratingWrap.querySelectorAll('[data-star-value]');
                    const input = document.getElementById('scoreInput');

                    function highlight(value) {
                        stars.forEach(function (button) {
                            const active = parseInt(button.dataset.starValue, 10) <= value;
                            const icon = button.querySelector('i');
                            button.classList.toggle('text-[#f1c40f]', active);
                            button.classList.toggle('text-[#4a4060]', !active);
                            icon.classList.toggle('bi-star-fill', active);
                            icon.classList.toggle('bi-star', !active);
                        });
                    }

                    stars.forEach(function (button) {
                        button.addEventListener('mouseenter', function () {
                            highlight(parseInt(button.dataset.starValue, 10));
                        });

                        button.addEventListener('mouseleave', function () {
                            highlight(parseInt(input.value || '0', 10));
                        });

                        button.addEventListener('click', function () {
                            input.value = button.dataset.starValue;
                            highlight(parseInt(input.value, 10));
                        });
                    });
                }

                const modal = document.getElementById('trailerModal');
                const openButton = document.querySelector('[data-open-trailer]');
                const iframe = document.querySelector('[data-trailer-frame]');
                const closeButtons = document.querySelectorAll('[data-close-trailer]');

                function withAutoplay(src) {
                    if (!src) return '';
                    return src + (src.includes('?') ? '&' : '?') + 'autoplay=1&rel=0';
                }

                function openTrailerModal() {
                    if (!modal || !iframe || !openButton) return;
                    iframe.src = withAutoplay(openButton.dataset.trailerSrc);
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                    document.body.classList.add('overflow-hidden');
                }

                function closeTrailerModal() {
                    if (!modal || !iframe) return;
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                    iframe.src = '';
                    document.body.classList.remove('overflow-hidden');
                }

                openButton?.addEventListener('click', openTrailerModal);
                closeButtons.forEach(function (button) {
                    button.addEventListener('click', closeTrailerModal);
                });

                modal?.addEventListener('click', function (event) {
                    if (event.target === modal) {
                        closeTrailerModal();
                    }
                });

                document.addEventListener('keydown', function (event) {
                    if (event.key === 'Escape') {
                        closeTrailerModal();
                    }
                });
            });
        </script>
    @endpush
</x-app-layout>
