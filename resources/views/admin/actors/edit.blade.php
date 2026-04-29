<x-admin-layout title="Edit Actor" subtitle="Ubah nama dan foto aktor.">
    <x-slot name="actions"><a href="{{ route('admin.actors.index') }}" class="admin-btn-outline"><i class="bi bi-arrow-left"></i> Back</a></x-slot>

    <section class="admin-panel">
        <header class="admin-panel-head"><h2>{{ $actor->name }}</h2><span class="admin-badge">{{ $actor->movies_count ?? 0 }} movie</span></header>
        <div class="admin-panel-body">
            <form id="actor-update" method="POST" action="{{ route('admin.actors.update', $actor) }}" enctype="multipart/form-data" class="admin-form">
                @csrf
                @method('PUT')
                <div class="admin-field">
                    <label for="name">Name</label>
                    <input id="name" class="admin-input" name="name" value="{{ old('name', $actor->name) }}" maxlength="100" required>
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>
                <div class="admin-field">
                    <label for="photo">Photo</label>
                    <input id="photo" class="admin-file" name="photo" type="file" accept="image/*">
                    @if($actor->photo_url)
                        <div class="admin-preview"><img src="{{ $actor->photo_url }}" alt="{{ $actor->name }}"><span>Photo saat ini</span></div>
                    @else
                        <p class="admin-help">Belum ada photo.</p>
                    @endif
                    <x-input-error :messages="$errors->get('photo')" class="mt-2" />
                </div>
            </form>
            <form id="actor-delete" method="POST" action="{{ route('admin.actors.destroy', $actor) }}" onsubmit="return confirm('Hapus actor ini?')">
                @csrf
                @method('DELETE')
            </form>
            <div class="admin-actions" style="justify-content:space-between;margin-top:1rem">
                <button form="actor-delete" class="admin-btn-danger" type="submit">Delete Actor</button>
                <div class="admin-actions">
                    <a href="{{ route('admin.actors.index') }}" class="admin-btn-outline">Cancel</a>
                    <button form="actor-update" class="admin-btn" type="submit">Save Actor</button>
                </div>
            </div>
        </div>
    </section>
</x-admin-layout>
