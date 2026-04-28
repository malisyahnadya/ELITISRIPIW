<x-app-layout>
    <div class="elit-page pb-20">
        <div class="elit-shell space-y-12 py-10">
            @if(session('success'))
                <div class="rounded-2xl border border-emerald-300/40 bg-emerald-500/15 px-5 py-3 text-sm font-bold text-emerald-100">
                    {{ session('success') }}
                </div>
            @endif

            {{-- ═══════════════════════════════════════════
                 SECTION: PROFILE CARD (read-only)
                 ═══════════════════════════════════════════ --}}
            <section class="elit-panel rounded-2xl p-6 lg:p-10" aria-label="Profile">

                <div class="flex flex-col gap-6 sm:flex-row sm:items-start sm:justify-between">

                    {{-- Kiri: Avatar + info utama --}}
                    <div class="flex items-center gap-5">

                        {{-- Avatar --}}
                        <div class="flex-shrink-0 h-24 w-24 rounded-full overflow-hidden ring-2 ring-violet-200/50 bg-[#4b3775] flex items-center justify-center">
                            @if($user->profile_photo_url)
                                <img src="{{ $user->profile_photo_url }}"
                                     alt="{{ $user->name }}"
                                     class="h-full w-full object-cover">
                            @else
                                <i class="bi bi-person-fill text-4xl text-violet-300/50"></i>
                            @endif
                        </div>

                        {{-- Nama, username, email --}}
                        <div>
                            <h1 class="text-2xl font-black text-white leading-tight">
                                {{ $user->name ?: $user->username }}
                            </h1>
                            <p class="mt-0.5 text-sm font-semibold text-violet-300/70">
                                &#64;{{ $user->username }}
                            </p>
                            <p class="mt-1 text-xs text-violet-200/50 flex items-center gap-1.5">
                                <i class="bi bi-envelope"></i>
                                {{ $user->email }}
                            </p>
                        </div>
                    </div>

                    {{-- Kanan: Tombol aksi --}}
                    <div class="flex flex-wrap items-center gap-3 sm:flex-col sm:items-end">
                        <a href="{{ route('profile.edit') }}"
                           class="elit-btn gap-2 px-5">
                            <i class="bi bi-pencil-square"></i>
                            Edit Profile
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="elit-ghost-btn gap-2 px-5 text-xs">
                                <i class="bi bi-box-arrow-right"></i>
                                Log Out
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Bio --}}
                <div class="mt-6 border-t border-violet-200/10 pt-6">
                    <p class="text-xs font-bold text-violet-300/50 uppercase tracking-widest mb-1">Bio</p>
                    <p class="text-sm leading-7 text-white/75 max-w-2xl">
                        {{ $user->bio ?: 'Belum ada bio. Klik Edit Profile untuk menambahkannya.' }}
                    </p>
                </div>

            </section>

            <section>
                <h2 class="elit-section-title">My Watch List</h2>
                <div class="mt-6 grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
                    @forelse($watchlist as $movie)
                        <x-movie-card :movie="$movie" :status="$movie->pivot->status" />
                    @empty
                        <a href="{{ route('search') }}" class="flex min-h-72 items-center justify-center rounded-xl bg-[#3a2860] text-6xl text-violet-200 transition hover:bg-[#4b3775]"><i class="bi bi-plus-circle"></i></a>
                    @endforelse
                </div>
                <div class="mt-6">{{ $watchlist->links() }}</div>
            </section>

            <section id="reviews">
                <h2 class="elit-section-title">Lasted reviews</h2>
                <div class="mt-6 grid gap-6 md:grid-cols-2 xl:grid-cols-3">
                    @forelse($reviewedMovies as $movie)
                        <article class="review-card">
                            <div class="flex items-center justify-between gap-4">
                                <div class="flex min-w-0 items-center gap-3">
                                    @if($user->profile_photo_url)
                                        <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" class="h-12 w-12 rounded-full object-cover">
                                    @else
                                        <span class="h-12 w-12 rounded-full bg-slate-100"></span>
                                    @endif
                                    <div class="min-w-0">
                                        <p class="truncate text-sm font-black text-white">{{ $user->name ?: $user->username }}</p>
                                        <p class="text-[11px] text-violet-100/70">{{ optional($movie->pivot->created_at)->format('d/m/Y') }}</p>
                                    </div>
                                </div>
                                <p class="text-xl font-black text-white">{{ number_format((float) $movie->average_score, 0) }}/5</p>
                            </div>
                            <h3 class="mt-4 truncate text-sm font-black uppercase text-violet-100">{{ $movie->title }}</h3>
                            <p class="mt-3 line-clamp-6 text-xs leading-5 text-white/80">{{ $movie->pivot->review_text }}</p>
                            <div class="mt-5 border-t border-white/80 pt-3 text-right text-xs text-violet-100/80">
                                <i class="bi bi-hand-thumbs-up"></i> 200k &nbsp; <i class="bi bi-hand-thumbs-down"></i> 200k
                            </div>
                        </article>
                    @empty
                        <div class="col-span-full rounded-2xl border border-dashed border-violet-200/40 p-8 text-center font-bold text-violet-100/80">Belum ada review.</div>
                    @endforelse
                </div>
                <div class="mt-6">{{ $reviewedMovies->links() }}</div>
            </section>
        </div>
    </div>
</x-app-layout>
