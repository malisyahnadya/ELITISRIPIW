<x-app-layout>


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6">
                    Daftar Film Terbaru
                </h3>

                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-6">
                    {{-- Nanti di Controller: $movies = Movie::all(); --}}
                    @forelse ($movies ?? [] as $movie)
                        <div class="bg-gray-100 dark:bg-gray-700 rounded-lg overflow-hidden shadow">
                            <img src="{{ $movie->poster_path ?? 'https://via.placeholder.com/300x450' }}" 
                                 alt="{{ $movie->title }}" 
                                 class="w-full h-64 object-cover">
                            
                            <div class="p-3">
                                <h4 class="font-bold text-sm text-gray-900 dark:text-white truncate">
                                    {{ $movie->title }}
                                </h4>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $movie->release_year }}
                                </p>
                                <a href="{{ route('movies.show', $movie->id) }}" 
                                   class="mt-3 block text-center bg-blue-600 hover:bg-blue-700 text-white text-xs py-2 rounded">
                                    Lihat Detail
                                </a>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 col-span-full text-center">Belum ada data film di database.</p>
                    @endforelse
                </div>

            </div>
        </div>
    </div>
</x-app-layout>

{{-- Catatan: Pastikan di Controller yang menampilkan view ini, sudah mengirimkan data $movies --}}
{{-- Contoh: return view('home', ['movies' => Movie::all()]); --}}