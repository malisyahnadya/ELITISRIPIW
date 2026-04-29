<x-admin-layout title="Add Director" subtitle="Tambahkan director baru untuk data cast & crew.">
    <x-slot name="actions">
        <a href="{{ route('admin.directors.index') }}" class="admin-btn-outline"><i class="bi bi-arrow-left"></i> Back</a>
    </x-slot>

    <section class="admin-panel">
        <header class="admin-panel-head"><h2>Director Form</h2><span class="admin-badge">Create</span></header>
        <div class="admin-panel-body">
            <form method="POST" action="{{ route('admin.directors.store') }}" enctype="multipart/form-data" class="admin-form">
                @csrf
                <div class="admin-form-grid">
                    <div class="admin-field">
                        <label for="name">Name</label>
                        <input id="name" name="name" value="{{ old('name') }}" maxlength="100" class="admin-input" required>
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>
                    <div class="admin-field">
                        <label for="photo">Photo</label>
                        <input id="photo" name="photo" type="file" accept="image/*" class="admin-file">
                        <div class="admin-help">Opsional. Format jpg, jpeg, png, atau webp. Maksimal 2 MB.</div>
                        <x-input-error :messages="$errors->get('photo')" class="mt-2" />
                    </div>
                </div>
                <div class="admin-actions" style="justify-content:flex-end;margin-top:1rem">
                    <a href="{{ route('admin.directors.index') }}" class="admin-btn-outline">Cancel</a>
                    <button type="submit" class="admin-btn">Save Director</button>
                </div>
            </form>
        </div>
    </section>
</x-admin-layout>
