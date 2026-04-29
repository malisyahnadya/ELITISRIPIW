<x-admin-layout title="Add Actor" subtitle="Tambahkan aktor baru untuk cast movie.">
    <x-slot name="actions"><a href="{{ route('admin.actors.index') }}" class="admin-btn-outline"><i class="bi bi-arrow-left"></i> Back</a></x-slot>

    <section class="admin-panel">
        <header class="admin-panel-head"><h2>Actor Form</h2><span class="admin-badge">Create</span></header>
        <div class="admin-panel-body">
            <form method="POST" action="{{ route('admin.actors.store') }}" enctype="multipart/form-data" class="admin-form">
                @csrf
                <div class="admin-field">
                    <label for="name">Name</label>
                    <input id="name" class="admin-input" name="name" value="{{ old('name') }}" maxlength="100" required>
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>
                <div class="admin-field">
                    <label for="photo">Photo</label>
                    <input id="photo" class="admin-file" name="photo" type="file" accept="image/*">
                    <x-input-error :messages="$errors->get('photo')" class="mt-2" />
                </div>
                <div class="admin-actions" style="justify-content:flex-end">
                    <a href="{{ route('admin.actors.index') }}" class="admin-btn-outline">Cancel</a>
                    <button class="admin-btn" type="submit">Save Actor</button>
                </div>
            </form>
        </div>
    </section>
</x-admin-layout>
