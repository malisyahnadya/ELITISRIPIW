<x-app-layout>
    <div class="min-h-screen bg-[#1c1527] px-4 py-10 text-white sm:px-6 lg:px-8">
        <div class="mx-auto max-w-7xl">
            <div class="mb-6 flex flex-wrap items-center justify-between gap-4 border-b border-white/10 pb-4">
                <div>
                    <h1 class="text-2xl font-black tracking-wide text-white">My Watch List</h1>
                    <p class="mt-1 text-sm text-[#a9a2b8]">Kelola film yang ingin kamu tonton.</p>
                </div>
                <a href="{{ route('search') }}" class="rounded-full border border-[#7a669f]/60 px-4 py-2 text-xs font-semibold text-white transition hover:bg-[#7a669f]/20">
                    Browse Movies
                </a>
            </div>

            @if (session('success'))
                <div class="mb-6 rounded-xl border border-emerald-400/30 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-100">
                    {{ session('success') }}
                </div>
            @endif

            @if ($watchlist->isEmpty())
                <div class="rounded-2xl border border-dashed border-white/10 bg-white/5 p-8 text-center text-[#a9a2b8]">
                    Watchlist kamu kosong. Mulai cari film favoritmu di
                    <a href="{{ route('search') }}" class="font-semibold text-[#f1c40f] hover:text-white">halaman search</a>.
                </div>
            @else
                <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6">
                    @foreach ($watchlist as $item)
                        @if ($item->movie)
                            <div class="space-y-2">
                                <x-movie-card :movie="$item->movie" :watchlist-status="$item->status" :fluid="true" />
                                <form method="POST" action="{{ route('watchlist.store', $item->movie) }}" class="flex items-center gap-2">
                                    @csrf
                                    <select name="status" class="flex-1 rounded-full border border-[#7a669f]/60 bg-[#1c1527] px-3 py-2 text-xs text-white">
                                        <option value="plan_to_watch" @selected($item->status === 'plan_to_watch')>Plan</option>
                                        <option value="watching" @selected($item->status === 'watching')>Watching</option>
                                        <option value="completed" @selected($item->status === 'completed')>Completed</option>
                                    </select>
                                    <button type="submit" class="rounded-full border border-[#7a669f] px-3 py-2 text-xs font-semibold text-white transition hover:bg-[#7a669f]">
                                        Update
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('watchlist.destroy', $item->movie) }}" onsubmit="return confirm('Hapus dari watchlist?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full rounded-full border border-red-300/40 px-3 py-2 text-xs font-semibold text-red-100 transition hover:bg-red-500/20">
                                        Remove
                                    </button>
                                </form>
                            </div>
                        @endif
                    @endforeach
                </div>

                <div class="mt-8">
                    {{ $watchlist->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
