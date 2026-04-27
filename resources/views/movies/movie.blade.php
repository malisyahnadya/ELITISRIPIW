<x-app-layout>
    <div class="bg-slate-950 text-slate-100">
        <h1>Perhatikan fungsi yang sudah ada di controller, dan routing di web.php</h1>
        <h2>Ini masih hasil generate AI, belum sesuai sama desain kita</h2>
        <div class="mx-auto max-w-6xl px-4 py-8 sm:px-6 lg:px-8">
            <a href="{{ url()->previous() }}" class="mb-4 inline-flex items-center gap-2 rounded-lg border border-slate-700 px-3 py-2 text-sm hover:bg-slate-800">
                <i class="bi bi-arrow-left"></i>
                Kembali
            </a>

            <section class="overflow-hidden rounded-2xl border border-slate-700 bg-slate-900">
                <div class="relative h-64 sm:h-80">
                    <img src="{{ $movie->banner_url ?? $movie->poster_url ?? 'https://via.placeholder.com/1200x420?text=No+Banner' }}" alt="{{ $movie->title }}" class="h-full w-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/60 to-transparent"></div>
                </div>

                <div class="grid gap-6 p-6 md:grid-cols-[220px_1fr]">
                    <div>
                        <img src="{{ $movie->poster_url ?? 'https://via.placeholder.com/360x540?text=No+Poster' }}" alt="{{ $movie->title }}" class="w-full rounded-xl border border-slate-700 object-cover shadow-lg">
                    </div>

                    <div>
                        <h1 class="text-3xl font-extrabold text-cyan-300">{{ $movie->title }}</h1>

                        <div class="mt-3 flex flex-wrap gap-2 text-sm">
                            <span class="rounded-full bg-slate-800 px-3 py-1">{{ $movie->release_year ?? '-' }}</span>
                            <span class="rounded-full bg-slate-800 px-3 py-1">{{ $movie->duration_formatted }}</span>
                            <span class="rounded-full bg-slate-800 px-3 py-1">{{ number_format($movie->average_score, 1) }}/5</span>
                            <span class="rounded-full bg-slate-800 px-3 py-1">{{ $movie->ratings_count }} rating</span>
                        </div>

                        <div class="mt-3 text-amber-300">
                            @foreach ($movie->average_score_star_icons as $icon)
                                <i class="bi {{ $icon }}"></i>
                            @endforeach
                        </div>

                        <p class="mt-5 text-sm leading-7 text-slate-200">{{ $movie->description ?: 'Belum ada deskripsi film.' }}</p>

                        <div class="mt-6 grid gap-4 text-sm sm:grid-cols-2">
                            <div>
                                <h2 class="mb-2 text-xs font-semibold uppercase tracking-wide text-slate-400">Genre</h2>
                                <div class="flex flex-wrap gap-2">
                                    @forelse ($movie->genres as $genre)
                                        <span class="rounded-full bg-cyan-900/50 px-3 py-1">{{ $genre->name }}</span>
                                    @empty
                                        <span class="text-slate-400">-</span>
                                    @endforelse
                                </div>
                            </div>

                            <div>
                                <h2 class="mb-2 text-xs font-semibold uppercase tracking-wide text-slate-400">Director</h2>
                                <div class="text-slate-200">
                                    @forelse ($movie->directors as $director)
                                        <span>{{ $director->name }}</span>@if (! $loop->last), @endif
                                    @empty
                                        <span class="text-slate-400">-</span>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        <div class="mt-6">
                            <h2 class="mb-2 text-xs font-semibold uppercase tracking-wide text-slate-400">Actors</h2>
                            <div class="text-sm text-slate-200">
                                @forelse ($movie->actors as $actor)
                                    <span>{{ $actor->name }}</span>@if (! $loop->last), @endif
                                @empty
                                    <span class="text-slate-400">-</span>
                                @endforelse
                            </div>
                        </div>

                        @if ($movie->trailer_embed_url)
                            <div class="mt-6 overflow-hidden rounded-xl border border-slate-700">
                                <iframe
                                    class="h-64 w-full"
                                    src="{{ $movie->trailer_embed_url }}"
                                    title="Trailer {{ $movie->title }}"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                    allowfullscreen
                                ></iframe>
                            </div>
                        @endif
                    </div>
                </div>
            </section>

        </div>
    </div>
</x-app-layout>
