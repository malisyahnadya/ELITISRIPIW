<x-admin-layout title="Edit Director" subtitle="Update data {{ $director->name }}.">
    <x-slot name="actions">
        <a href="{{ route('admin.directors.index') }}" class="admin-btn-outline"><i class="bi bi-arrow-left"></i> Back</a>
    </x-slot>

    <section class="admin-panel">
        <header class="admin-panel-head">
            <h2>{{ $director->name }}</h2>
            <span class="admin-badge">{{ $director->movies_count ?? 0 }} movie</span>
        </header>
        <div class="admin-panel-body">
            <form id="director-update-form" method="POST" action="{{ route('admin.directors.update', $director) }}" enctype="multipart/form-data" class="admin-form">
                @csrf
                @method('PUT')
                <div class="admin-form-grid">
                    <div class="admin-field">
                        <label for="name">Name</label>
                        <input id="name" name="name" value="{{ old('name', $director->name) }}" maxlength="100" class="admin-input" required>
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>
                    <div class="admin-field">
                        <label for="photo">Photo Baru</label>
                        <input id="photo" name="photo" type="file" accept="image/*" class="admin-file">
                        @if($director->photo_url)
                            <div class="admin-preview"><img src="{{ $director->photo_url }}" alt="{{ $director->name }}"><span>Photo saat ini</span></div>
                        @else
                            <p class="admin-help">Belum ada photo. Kosongkan jika tidak ingin mengganti.</p>
                        @endif
                        <x-input-error :messages="$errors->get('photo')" class="mt-2" />
                    </div>
                </div>
            </form>

            <form id="director-delete-form" method="POST" action="{{ route('admin.directors.destroy', $director) }}" onsubmit="return confirm('Hapus director ini?')">
                @csrf
                @method('DELETE')
            </form>

            <div class="admin-actions" style="justify-content:space-between;margin-top:1rem">
                <button type="submit" form="director-delete-form" class="admin-btn-danger">Delete Director</button>
                <div class="admin-actions">
                    <a href="{{ route('admin.directors.index') }}" class="admin-btn-outline">Cancel</a>
                    <button type="submit" form="director-update-form" class="admin-btn">Save Director</button>
                </div>
            </div>
        </div>
    </section>
</x-admin-layout>
