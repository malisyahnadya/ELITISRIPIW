<x-admin-layout title="CRUD Movie" subtitle="Tambah, edit, hapus, dan pantau data movie.">
    <x-slot name="actions">
        <a href="{{ route('admin.movies.create') }}" class="admin-btn"><i class="bi bi-plus-lg"></i> Add Movie</a>
    </x-slot>

    <section class="admin-search-card">
        <form method="GET" action="{{ route('admin.movies.index') }}" class="admin-filter-form">
            <input class="admin-input" name="search" value="{{ $search }}" placeholder="Search movie title">
            <select class="admin-select" name="sort">
                <option value="asc" @selected($sort === 'asc')>Title A-Z</option>
                <option value="desc" @selected($sort === 'desc')>Title Z-A</option>
            </select>
            <button class="admin-btn" type="submit"><i class="bi bi-search"></i> Search</button>
            @if($search)
                <a href="{{ route('admin.movies.index') }}" class="admin-btn-outline">Reset</a>
            @endif
        </form>
        <span class="admin-badge">{{ $movies->total() }} movie</span>
    </section>

    <div style="height:1rem"></div>

    <section class="admin-panel">
        <header class="admin-panel-head">
            <h2>Movie List</h2>
            <a href="{{ route('admin.movies.create') }}" class="admin-btn">+ Add Movie</a>
        </header>
        <div class="admin-panel-body admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Film</th>
                        <th>Year</th>
                        <th>Duration</th>
                        <th>Genre</th>
                        <th>Director</th>
                        <th>Rating</th>
                        <th>Reviews</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($movies as $movie)
                        <tr>
                            <td>{{ $movie->title }}</td>
                            <td>{{ $movie->release_year ?? '-' }}</td>
                            <td>{{ $movie->duration_formatted }}</td>
                            <td>
                                @forelse($movie->genres as $genre)
                                    <span class="admin-badge">{{ $genre->name }}</span>
                                @empty
                                    -
                                @endforelse
                            </td>
                            <td>{{ $movie->directors->pluck('name')->join(', ') ?: '-' }}</td>
                            <td>{{ number_format((float) $movie->average_score, 1) }}</td>
                            <td>{{ $movie->reviews_count ?? 0 }}</td>
                            <td>
                                <div class="admin-actions">
                                    <a href="{{ route('admin.movies.edit', $movie) }}" class="admin-btn-soft">Edit</a>
                                    <form method="POST" action="{{ route('admin.movies.destroy', $movie) }}" onsubmit="return confirm('Hapus movie ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="admin-btn-danger">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="admin-empty">Belum ada movie. Klik Add Movie untuk membuat data pertama.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <div class="admin-pagination">{{ $movies->links() }}</div>
</x-admin-layout>
