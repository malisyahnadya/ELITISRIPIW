@props([
    'movie',
    'showWatchlist' => true,
    'watchlistStatus' => null,
    'fluid' => false,
])

@php
    $rating = (float) ($movie->average_score ?? 0);
    $poster = $movie->poster_url ?: asset('images/default-poster.svg');
    $statusLabel = $watchlistStatus ? ucwords(str_replace('_', ' ', $watchlistStatus)) : null;
    $cardClass = $fluid
        ? 'group flex w-full flex-col rounded-xl bg-[#2f2543] p-3 shadow-[0_16px_34px_rgba(0,0,0,.22)] ring-1 ring-white/5 transition duration-200 hover:-translate-y-1 hover:ring-[#9c89c4]/50'
        : 'group flex min-w-[165px] max-w-[165px] flex-col rounded-xl bg-[#2f2543] p-3 shadow-[0_16px_34px_rgba(0,0,0,.22)] ring-1 ring-white/5 transition duration-200 hover:-translate-y-1 hover:ring-[#9c89c4]/50';
@endphp

<article {{ $attributes->merge(['class' => $cardClass]) }}>
    <a href="{{ route('movies.show', $movie) }}" class="block overflow-hidden rounded-lg bg-[#312a4a]">
        <img src="{{ $poster }}" alt="{{ $movie->title }} poster" class="h-[220px] w-full object-cover transition duration-300 group-hover:scale-105">
    </a>

    <div class="mt-3 flex flex-1 flex-col">
        <a href="{{ route('movies.show', $movie) }}" class="truncate text-[0.84rem] font-extrabold uppercase tracking-wide text-white hover:text-[#f1c40f]">
            {{ $movie->title }} @if($movie->release_year) ({{ $movie->release_year }}) @endif
        </a>

        @if($movie->duration_minutes)
            <div class="mt-1 text-[0.72rem] text-[#a9a2b8]">
                <i class="bi bi-clock"></i> {{ $movie->duration_formatted }}
            </div>
        @endif

        <div class="mt-2 text-[0.78rem] font-bold text-[#f1c40f]">
            @if($rating > 0)
                @foreach ($movie->average_score_star_icons as $icon)
                    <i class="bi {{ $icon }}"></i>
                @endforeach
                <span class="ml-1 text-white/90">{{ number_format($rating, 1) }}</span>
            @else
                <span class="text-[#a9a2b8]">No ratings yet</span>
            @endif
        </div>

        <div class="mt-3 space-y-2">
            <a href="{{ route('movies.show', $movie) }}" class="block rounded-full border border-[#7a669f] px-3 py-1.5 text-center text-xs font-semibold text-white transition hover:bg-[#7a669f]">
                See Details
            </a>

            @if($statusLabel)
                <div class="rounded-full border border-[#7a669f]/70 bg-[#7a669f]/20 px-3 py-1.5 text-center text-xs font-semibold text-[#d6c6ff]">
                    {{ $statusLabel }}
                </div>
            @elseif($showWatchlist)
                @auth
                    <form method="POST" action="{{ route('watchlist.store', $movie) }}">
                        @csrf
                        <input type="hidden" name="status" value="plan_to_watch">
                        <button type="submit" class="w-full rounded-full border border-[#7a669f] px-3 py-1.5 text-center text-xs font-semibold text-white transition hover:bg-[#7a669f]">
                            Add To Watch List
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="block rounded-full border border-[#7a669f] px-3 py-1.5 text-center text-xs font-semibold text-white transition hover:bg-[#7a669f]">
                        Add To Watch List
                    </a>
                @endauth
            @endif
        </div>
    </div>
</article>
