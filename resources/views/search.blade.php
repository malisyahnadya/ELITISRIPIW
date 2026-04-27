<x-app-layout>
    <div class="bg-slate-950 text-slate-100">
        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            <header class="mb-6">
                <h1 class="text-2xl font-bold text-cyan-300">Search Movies</h1>
                <p class="mt-1 text-sm text-slate-300">Temukan film berdasarkan judul, genre, tahun rilis, dan score minimal.</p>
            </header>

            <form method="GET" action="{{ route('search') }}" class="rounded-xl border border-slate-700 bg-slate-900 p-4">
                <div class="grid grid-cols-1 gap-3 md:grid-cols-4">
                    <div>
                        <label for="q" class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-300">Keyword</label>
                        <input id="q" name="q" value="{{ $q }}" placeholder="Contoh: Spider" class="w-full rounded-lg border border-slate-600 bg-slate-950 px-3 py-2 text-sm text-slate-100 placeholder:text-slate-500 focus:border-cyan-500 focus:outline-none">
                    </div>

                    <div>
                        <label for="genre_id" class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-300">Genre</label>
                        <select id="genre_id" name="genre_id" class="w-full rounded-lg border border-slate-600 bg-slate-950 px-3 py-2 text-sm text-slate-100 focus:border-cyan-500 focus:outline-none">
                            <option value="">Semua Genre</option>
                            @foreach ($genres as $genre)
                                <option value="{{ $genre->id }}" @selected((string) $selectedGenreId === (string) $genre->id)>{{ $genre->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="release_year" class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-300">Tahun Rilis</label>
                        <select id="release_year" name="release_year" class="w-full rounded-lg border border-slate-600 bg-slate-950 px-3 py-2 text-sm text-slate-100 focus:border-cyan-500 focus:outline-none">
                            <option value="">Semua Tahun</option>
                            @foreach ($releaseYears as $year)
                                <option value="{{ $year }}" @selected((string) $selectedReleaseYear === (string) $year)>{{ $year }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="min_score" class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-300">Min Score</label>
                        <select id="min_score" name="min_score" class="w-full rounded-lg border border-slate-600 bg-slate-950 px-3 py-2 text-sm text-slate-100 focus:border-cyan-500 focus:outline-none">
                            <option value="">Tanpa Batas</option>
                            @for ($score = 1; $score <= 5; $score++)
                                <option value="{{ $score }}" @selected((string) $selectedMinScore === (string) $score)>{{ $score }} / 5</option>
                            @endfor
                        </select>
                    </div>
                </div>

                <div class="mt-4 flex gap-2">
                    <button type="submit" class="rounded-lg bg-cyan-600 px-4 py-2 text-sm font-semibold hover:bg-cyan-500">Cari</button>
                    <a href="{{ route('search') }}" class="rounded-lg border border-slate-600 px-4 py-2 text-sm font-semibold hover:bg-slate-800">Reset</a>
                </div>
            </form>

            <div class="mt-6 text-sm text-slate-300">
                Menampilkan {{ $movies->total() }} hasil.
            </div>

            <section class="mt-4">
                @if ($movies->isEmpty())
                    <div class="rounded-xl border border-dashed border-slate-700 bg-slate-900 p-6 text-center text-slate-300">
                        Tidak ada film yang cocok dengan filter saat ini.
                    </div>
                @else
                    <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-6">
                        @foreach ($movies as $movie)
                            <a href="{{ route('movies.show', $movie) }}" class="overflow-hidden rounded-xl border border-slate-700 bg-slate-900 shadow transition hover:-translate-y-1 hover:border-cyan-500/60">
                                <img src="{{ $movie->poster_url ?? 'https://via.placeholder.com/320x480?text=No+Poster' }}" alt="{{ $movie->title }}" class="h-56 w-full object-cover">
                                <div class="p-3">
                                    <h2 class="truncate text-sm font-semibold">{{ $movie->title }}</h2>
                                    <p class="mt-1 text-xs text-slate-400">{{ $movie->release_year ?? '-' }} • {{ $movie->duration_formatted }}</p>
                                    <p class="mt-2 text-xs text-cyan-300">{{ number_format($movie->average_score, 1) }}/5 • {{ $movie->ratings_count }} rating</p>
                                </div>
                            </a>
                        @endforeach
                    </div>

                    <div class="mt-6">
                        {{ $movies->links() }}
                    </div>
                @endif
            </section>
        </div>
    </div>
</x-app-layout>
