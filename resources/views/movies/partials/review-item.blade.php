{{--
    Partial: Single Review Item
    Variables expected:
      $review  – Review model (with user relation loaded, likes_count available)
--}}
<article class="review-card group transition-transform duration-200 hover:-translate-y-0.5">

    {{-- Header: avatar + user info + score --}}
    <div class="flex items-start justify-between gap-4">

        {{-- Left: avatar + name + date --}}
        <div class="flex items-center gap-3 min-w-0">

            {{-- Avatar --}}
            <div class="flex-shrink-0 w-11 h-11 rounded-full overflow-hidden ring-2 ring-violet-300/20 bg-[#4b3775] flex items-center justify-center">
                @if ($review->user?->profile_photo_url)
                    <img
                        src="{{ $review->user->profile_photo_url }}"
                        alt="{{ $review->user->name }}"
                        class="w-full h-full object-cover"
                        loading="lazy"
                        onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div style="display:none" class="w-full h-full flex items-center justify-center text-violet-300/60">
                        <i class="bi bi-person-fill text-lg"></i>
                    </div>
                @else
                    <div class="w-full h-full flex items-center justify-center text-violet-300/60">
                        <i class="bi bi-person-fill text-lg"></i>
                    </div>
                @endif
            </div>

            {{-- Name + date --}}
            <div class="min-w-0">
                <p class="font-black text-white text-sm truncate">
                    {{ $review->user?->name ?: ($review->user?->username ?: 'Anonymous') }}
                </p>
                <p class="text-[11px] text-violet-300/60 mt-0.5">
                    {{ optional($review->created_at)->format('d M Y') }}
                </p>
            </div>
        </div>

        {{-- Right: individual star rating for this review --}}
        {{-- Note: each review doesn't store score, so we show movie average as reference --}}
        <div class="flex-shrink-0 flex items-center gap-1 text-amber-400 text-sm">
            @foreach ($review->movie->average_score_star_icons ?? [] as $icon)
                <i class="bi {{ $icon }}"></i>
            @endforeach
        </div>

    </div>

    {{-- Review text --}}
    <p class="mt-4 text-sm leading-relaxed text-white/80">
        {{ $review->review_text }}
    </p>

    {{-- Footer: likes count --}}
    <div class="mt-4 flex items-center gap-4 text-xs text-violet-300/60">
        @php
            $likedByCurrentUser = (bool) ($review->liked_by_current_user ?? false);
        @endphp

        @auth
            <form method="POST" action="{{ route('reviews.like.toggle', $review) }}">
                @csrf
                <button
                    type="submit"
                    class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 font-semibold transition {{ $likedByCurrentUser ? 'bg-pink-500/20 text-pink-100' : 'hover:bg-white/10 hover:text-white' }}"
                    aria-label="{{ $likedByCurrentUser ? 'Unlike review' : 'Like review' }}"
                >
                    <i class="bi {{ $likedByCurrentUser ? 'bi-heart-fill' : 'bi-heart' }}"></i>
                    {{ $review->likes_count ?? 0 }} Likes
                </button>
            </form>
        @else
            <a href="{{ route('login') }}" class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 hover:bg-white/10 hover:text-white">
                <i class="bi bi-heart"></i>
                {{ $review->likes_count ?? 0 }} Likes
            </a>
        @endauth

        <span class="flex items-center gap-1.5">
            <i class="bi bi-clock"></i>
            {{ optional($review->created_at)->diffForHumans() }}
        </span>
    </div>

</article>
