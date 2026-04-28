{{--
    Partial: Cast / Crew Card
    Variables expected:
      $person  – Actor or Director model
      $role    – (string) display role, e.g. "Director", "Actor", or pivot role_name
--}}
<article class="group flex-shrink-0 w-36 flex flex-col items-center text-center">

    {{-- Profile photo --}}
    <div class="w-24 h-24 rounded-full overflow-hidden ring-2 ring-violet-300/20 group-hover:ring-violet-300/60 transition-all duration-300 bg-[#3a2860] flex items-center justify-center shadow-lg">
        @if (!empty($person->photo_url))
            <img
                src="{{ $person->photo_url }}"
                alt="{{ $person->name }}"
                class="w-full h-full object-cover"
                loading="lazy"
                onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
            {{-- Fallback shown via JS onerror --}}
            <div style="display:none" class="w-full h-full flex items-center justify-center text-violet-300/60">
                <i class="bi bi-person-fill text-3xl"></i>
            </div>
        @else
            <div class="w-full h-full flex items-center justify-center text-violet-300/60">
                <i class="bi bi-person-fill text-3xl"></i>
            </div>
        @endif
    </div>

    {{-- Name & role --}}
    <p class="mt-3 text-sm font-bold leading-tight text-white line-clamp-2">{{ $person->name }}</p>
    <p class="mt-0.5 text-xs text-violet-300/65 font-medium">{{ $role }}</p>

</article>
