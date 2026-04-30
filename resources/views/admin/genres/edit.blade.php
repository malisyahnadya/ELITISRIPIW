<x-admin-layout title="Edit Genre" subtitle="Ubah nama genre.">
    <x-slot name="actions"><a href="{{ route('admin.genres.index') }}" class="admin-btn-outline"><i class="bi bi-arrow-left"></i> Back</a></x-slot>

    <section class="admin-panel">
        <header class="admin-panel-head"><h2>{{ $genre->name }}</h2><span class="admin-badge">{{ $genre->movies_count ?? 0 }} movie</span></header>
        <div class="admin-panel-body">
            <form id="genre-update" method="POST" action="{{ route('admin.genres.update', $genre) }}" class="admin-form">
                @csrf
                @method('PUT')
                <div class="admin-field">
                    <label for="name">Name</label>
                    <input id="name" class="admin-input" name="name" value="{{ old('name', $genre->name) }}" maxlength="50" required>
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>
            </form>
            <form id="genre-delete" method="POST" action="{{ route('admin.genres.destroy', $genre) }}" onsubmit="return confirm('Hapus genre ini?')">
                @csrf
                @method('DELETE')
            </form>
            <div class="admin-actions" style="justify-content:space-between;margin-top:1rem">
                <button form="genre-delete" class="admin-btn-danger" type="submit">Delete Genre</button>
                <div class="admin-actions">
                    <a href="{{ route('admin.genres.index') }}" class="admin-btn-outline">Cancel</a>
                    <button form="genre-update" class="admin-btn" type="submit">Save Genre</button>
                </div>
            </div>
        </div>
    </section>
</x-admin-layout>
