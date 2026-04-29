<x-admin-layout title="CRUD Actor" subtitle="Kelola data aktor dan jumlah movie terkait.">
    <x-slot name="actions">
        <a href="{{ route('admin.actors.create') }}" class="admin-btn"><i class="bi bi-plus-lg"></i> Add Actor</a>
    </x-slot>

    <section class="admin-search-card">
        <form method="GET" action="{{ route('admin.actors.index') }}" class="admin-filter-form">
            <input class="admin-input" name="search" value="{{ $search }}" placeholder="Search actor">
            <select class="admin-select" name="sort">
                <option value="asc" @selected($sort === 'asc')>Name A-Z</option>
                <option value="desc" @selected($sort === 'desc')>Name Z-A</option>
            </select>
            <button class="admin-btn" type="submit"><i class="bi bi-search"></i> Search</button>
            @if($search)<a href="{{ route('admin.actors.index') }}" class="admin-btn-outline">Reset</a>@endif
        </form>
        <span class="admin-badge">{{ $actors->total() }} actor</span>
    </section>

    <div style="height:1rem"></div>

    <section class="admin-panel">
        <header class="admin-panel-head">
            <h2>Actors</h2>
            <a href="{{ route('admin.actors.create') }}" class="admin-btn">+ Add Actor</a>
        </header>
        <div class="admin-panel-body admin-table-wrap">
            <table class="admin-table">
                <thead><tr><th>Name</th><th>Movies</th><th>Photo</th><th>Action</th></tr></thead>
                <tbody>
                    @forelse($actors as $actor)
                        <tr>
                            <td>{{ $actor->name }}</td>
                            <td>{{ $actor->movies_count ?? 0 }}</td>
                            <td>{{ $actor->photo_path ? 'Uploaded' : '-' }}</td>
                            <td>
                                <div class="admin-actions">
                                    <a href="{{ route('admin.actors.edit', $actor) }}" class="admin-btn-soft">Edit</a>
                                    <form method="POST" action="{{ route('admin.actors.destroy', $actor) }}" onsubmit="return confirm('Hapus actor ini? Relasi movie actor akan dilepas.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="admin-btn-danger">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="admin-empty">Belum ada actor. Klik Add Actor untuk membuat data.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
    <div class="admin-pagination">{{ $actors->links() }}</div>
</x-admin-layout>
