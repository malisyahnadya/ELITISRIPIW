<x-app-layout>
    <div class="elit-page min-h-screen pb-20">
        <div class="elit-shell py-10">

            <div class="mb-8 flex flex-wrap items-center justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-black text-white">
                        Import Movie from TMDB
                    </h1>
                    <p class="mt-2 text-sm font-semibold text-violet-100/70">
                        Import movie otomatis lengkap dengan poster, banner, trailer, genre, actor, dan director.
                    </p>
                </div>

                <a href="{{ route('admin.movies.index') }}"
                   class="rounded-xl bg-violet-200 px-5 py-3 text-sm font-black text-[#2b1d46] hover:bg-white">
                    Back to Movies
                </a>
            </div>

            @if(session('success'))
                <div class="mb-6 rounded-2xl border border-emerald-300/30 bg-emerald-500/15 px-5 py-4 font-bold text-emerald-100">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 rounded-2xl border border-red-300/30 bg-red-500/15 px-5 py-4 font-bold text-red-100">
                    {{ session('error') }}
                </div>
            @endif

            <div class="rounded-3xl border border-violet-200/20 bg-[#2a1b46] p-6 shadow-2xl">
                <form method="POST" action="{{ route('admin.tmdb-import.store') }}" class="space-y-6">
                    @csrf

                    <div>
                        <label class="mb-2 block text-sm font-black text-violet-100">
                            Movie Title / TMDB ID
                        </label>

                        <input
                            type="text"
                            name="query"
                            value="{{ old('query') }}"
                            placeholder="Contoh: Bad Boys atau 9737"
                            class="w-full rounded-2xl border border-violet-200/20 bg-[#1b1230] px-5 py-4 text-white outline-none focus:border-violet-200"
                        >

                        @error('query')
                            <p class="mt-2 text-sm font-bold text-red-300">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid gap-5 md:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-sm font-black text-violet-100">
                                Import Type
                            </label>

                            <select
                                name="import_type"
                                class="w-full rounded-2xl border border-violet-200/20 bg-[#1b1230] px-5 py-4 text-white outline-none focus:border-violet-200"
                            >
                                <option value="title" @selected(old('import_type') === 'title')>
                                    Search by Title
                                </option>
                                <option value="id" @selected(old('import_type') === 'id')>
                                    Search by TMDB ID
                                </option>
                            </select>
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-black text-violet-100">
                                Cast Limit
                            </label>

                            <input
                                type="number"
                                name="cast_limit"
                                value="{{ old('cast_limit', 12) }}"
                                min="1"
                                max="50"
                                class="w-full rounded-2xl border border-violet-200/20 bg-[#1b1230] px-5 py-4 text-white outline-none focus:border-violet-200"
                            >
                        </div>
                    </div>

                    <label class="flex items-center gap-3 rounded-2xl border border-violet-200/20 bg-[#1b1230] px-5 py-4">
                        <input
                            type="checkbox"
                            name="overwrite"
                            value="1"
                            class="h-5 w-5 rounded border-violet-200"
                        >

                        <span class="text-sm font-bold text-violet-100">
                            Overwrite data lama dari TMDB
                        </span>
                    </label>

                    <div class="flex justify-end">
                        <button
                            type="submit"
                            class="rounded-2xl bg-violet-200 px-8 py-4 text-sm font-black text-[#2b1d46] shadow-xl hover:bg-white"
                        >
                            Import Movie
                        </button>
                    </div>
                </form>
            </div>

            @if(session('tmdb_output'))
                <div class="mt-8 rounded-3xl border border-violet-200/20 bg-[#1b1230] p-6">
                    <h2 class="mb-4 text-lg font-black text-white">
                        TMDB Output
                    </h2>

                    <pre class="max-h-80 overflow-auto whitespace-pre-wrap rounded-2xl bg-black/30 p-5 text-sm text-violet-100">{{ session('tmdb_output') }}</pre>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>