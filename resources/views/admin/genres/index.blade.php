<x-admin-layout title="CRUD Genre" subtitle="Kelola kategori genre untuk filter dan relasi movie.">
    <x-slot name="actions">
        <a href="{{ route('admin.genres.create') }}" class="admin-btn"><i class="bi bi-plus-lg"></i> Add Genre</a>
    </x-slot>

    <section class="admin-search-card">
        <form method="GET" action="{{ route('admin.genres.index') }}" class="admin-filter-form">
            <input class="admin-input" name="search" value="{{ $search }}" placeholder="Search genre">
            <select class="admin-select" name="sort">
                <option value="asc" @selected($sort === 'asc')>Name A-Z</option>
                <option value="desc" @selected($sort === 'desc')>Name Z-A</option>
            </select>
            <button class="admin-btn" type="submit"><i class="bi bi-search"></i> Search</button>
            @if($search)<a href="{{ route('admin.genres.index') }}" class="admin-btn-outline">Reset</a>@endif
        </form>
        <span class="admin-badge">{{ $genres->total() }} genre</span>
    </section>

    <div style="height:1rem"></div>

    <section class="admin-panel">
        <header class="admin-panel-head">
            <h2>Genres</h2>
            <a href="{{ route('admin.genres.create') }}" class="admin-btn">+ Add Genre</a>
        </header>
        <div class="admin-panel-body admin-table-wrap">
            <table class="admin-table">
                <thead><tr><th>Name</th><th>Movies</th><th>Action</th></tr></thead>
                <tbody>
                    @forelse($genres as $genre)
                        <tr>
                            <td>{{ $genre->name }}</td>
                            <td>{{ $genre->movies_count ?? 0 }}</td>
                            <td>
                                <div class="admin-actions">
                                    <a href="{{ route('admin.genres.edit', $genre) }}" class="admin-btn-soft">Edit</a>
                                    <form method="POST" action="{{ route('admin.genres.destroy', $genre) }}" onsubmit="return confirm('Hapus genre ini? Relasi movie genre akan dilepas.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="admin-btn-danger">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="admin-empty">Belum ada genre. Klik Add Genre untuk membuat data.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
    <div class="admin-pagination">{{ $genres->links() }}</div>
</x-admin-layout>
