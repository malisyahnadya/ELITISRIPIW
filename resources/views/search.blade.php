<x-app-layout>
    <div class="min-h-screen bg-[#1c1527] px-4 py-10 text-white sm:px-6 lg:px-8">
        <div class="mx-auto max-w-7xl">
            <header class="mb-6">
                <h1 class="text-2xl font-black tracking-wide text-white">Search Movies</h1>
                <p class="mt-1 text-sm text-[#a9a2b8]">Temukan film berdasarkan judul, genre, tahun rilis, dan score minimal.</p>
            </header>

            <form method="GET" action="{{ route('search') }}" class="rounded-2xl border border-[#7a669f]/25 bg-[#2f2543] p-4 shadow-[0_14px_35px_rgba(0,0,0,.18)]">
                <div class="grid grid-cols-1 gap-3 md:grid-cols-4">
                    <div>
                        <label for="q" class="mb-1 block text-xs font-bold uppercase tracking-wide text-[#d6c6ff]">Keyword</label>
                        <input id="q" name="q" value="{{ $q }}" placeholder="Contoh: Spider" class="w-full rounded-lg border border-[#7a669f]/50 bg-[#1c1527] px-3 py-2 text-sm text-white placeholder:text-[#a9a2b8] focus:border-[#a855f7] focus:outline-none focus:ring-[#a855f7]">
                    </div>

                    <div>
                        <label for="genre_id" class="mb-1 block text-xs font-bold uppercase tracking-wide text-[#d6c6ff]">Genre</label>
                        <select id="genre_id" name="genre_id" class="w-full rounded-lg border border-[#7a669f]/50 bg-[#1c1527] px-3 py-2 text-sm text-white focus:border-[#a855f7] focus:outline-none focus:ring-[#a855f7]">
                            <option value="">Semua Genre</option>
                            @foreach ($genres as $genre)
                                <option value="{{ $genre->id }}" @selected((string) $selectedGenreId === (string) $genre->id)>{{ $genre->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="release_year" class="mb-1 block text-xs font-bold uppercase tracking-wide text-[#d6c6ff]">Tahun Rilis</label>
                        <select id="release_year" name="release_year" class="w-full rounded-lg border border-[#7a669f]/50 bg-[#1c1527] px-3 py-2 text-sm text-white focus:border-[#a855f7] focus:outline-none focus:ring-[#a855f7]">
                            <option value="">Semua Tahun</option>
                            @foreach ($releaseYears as $year)
                                <option value="{{ $year }}" @selected((string) $selectedReleaseYear === (string) $year)>{{ $year }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="min_score" class="mb-1 block text-xs font-bold uppercase tracking-wide text-[#d6c6ff]">Min Score</label>
                        <select id="min_score" name="min_score" class="w-full rounded-lg border border-[#7a669f]/50 bg-[#1c1527] px-3 py-2 text-sm text-white focus:border-[#a855f7] focus:outline-none focus:ring-[#a855f7]">
                            <option value="">Tanpa Batas</option>
                            @for ($score = 1; $score <= 5; $score++)
                                <option value="{{ $score }}" @selected((string) $selectedMinScore === (string) $score)>{{ $score }} / 5</option>
                            @endfor
                        </select>
                    </div>
                </div>

                <div class="mt-4 flex gap-2">
                    <button type="submit" class="rounded-lg bg-[#7a669f] px-4 py-2 text-sm font-bold text-white transition hover:bg-[#a855f7]">Cari</button>
                    <a href="{{ route('search') }}" class="rounded-lg border border-[#7a669f]/60 px-4 py-2 text-sm font-bold text-white transition hover:bg-[#7a669f]/20">Reset</a>
                </div>
            </form>

            <div class="mt-6 text-sm text-[#a9a2b8]">
                Menampilkan <strong class="text-white">{{ $movies->total() }}</strong> hasil.
            </div>

            <section class="mt-4">
                @if ($movies->isEmpty())
                    <div class="rounded-2xl border border-dashed border-white/10 bg-white/5 p-8 text-center text-[#a9a2b8]">
                        Tidak ada film yang cocok dengan filter saat ini.
                    </div>
                @else
                    <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6">
                        @foreach ($movies as $movie)
                            <x-movie-card :movie="$movie" :fluid="true" />
                        @endforeach
                    </div>

                    <div class="mt-8">
                        {{ $movies->links() }}
                    </div>
                @endif
            </section>
        </div>
    </div>
</x-app-layout>
