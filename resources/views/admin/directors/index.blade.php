<x-admin-layout title="CRUD Director" subtitle="Kelola data sutradara dan jumlah movie yang terhubung.">
    <x-slot name="actions">
        <a href="{{ route('admin.directors.create') }}" class="admin-btn"><i class="bi bi-plus-lg"></i> Add Director</a>
    </x-slot>

    <section class="admin-search-card">
        <form method="GET" action="{{ route('admin.directors.index') }}" class="admin-filter-form">
            <input class="admin-input" type="search" name="search" value="{{ $search }}" placeholder="Search director name">
            <select class="admin-select" name="sort">
                <option value="asc" @selected($sort === 'asc')>Name A-Z</option>
                <option value="desc" @selected($sort === 'desc')>Name Z-A</option>
            </select>
            <button class="admin-btn" type="submit"><i class="bi bi-search"></i> Search</button>
            @if($search)<a href="{{ route('admin.directors.index') }}" class="admin-btn-outline">Reset</a>@endif
        </form>
        <span class="admin-badge">{{ $directors->total() }} director</span>
    </section>

    <div style="height:1rem"></div>

    <section class="admin-panel">
        <header class="admin-panel-head">
            <h2>Directors</h2>
            <a href="{{ route('admin.directors.create') }}" class="admin-btn">+ Add Director</a>
        </header>
        <div class="admin-panel-body admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Photo</th>
                        <th>Name</th>
                        <th>Movies</th>
                        <th>Created</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($directors as $director)
                        <tr>
                            <td>
                                @if ($director->photo_url)
                                    <img src="{{ $director->photo_url }}" alt="{{ $director->name }}" class="admin-avatar">
                                @else
                                    <span class="admin-avatar">{{ strtoupper(substr($director->name, 0, 1)) }}</span>
                                @endif
                            </td>
                            <td>{{ $director->name }}</td>
                            <td>{{ $director->movies_count }}</td>
                            <td>{{ optional($director->created_at)->format('Y-m-d') ?? '-' }}</td>
                            <td>
                                <div class="admin-actions">
                                    <a href="{{ route('admin.directors.edit', $director) }}" class="admin-btn-soft">Edit</a>
                                    <form method="POST" action="{{ route('admin.directors.destroy', $director) }}" onsubmit="return confirm('Hapus director ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="admin-btn-danger" type="submit">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="admin-empty">Belum ada director. Klik Add Director untuk menambahkan data pertama.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <div class="admin-pagination">{{ $directors->links() }}</div>
</x-admin-layout>
