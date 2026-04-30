<x-admin-layout title="Add Genre" subtitle="Tambahkan kategori genre baru.">
    <x-slot name="actions"><a href="{{ route('admin.genres.index') }}" class="admin-btn-outline"><i class="bi bi-arrow-left"></i> Back</a></x-slot>

    <section class="admin-panel">
        <header class="admin-panel-head"><h2>Genre Form</h2><span class="admin-badge">Create</span></header>
        <div class="admin-panel-body">
            <form method="POST" action="{{ route('admin.genres.store') }}" class="admin-form">
                @csrf
                <div class="admin-field">
                    <label for="name">Name</label>
                    <input id="name" class="admin-input" name="name" value="{{ old('name') }}" maxlength="50" required>
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>
                <div class="admin-actions" style="justify-content:flex-end">
                    <a href="{{ route('admin.genres.index') }}" class="admin-btn-outline">Cancel</a>
                    <button class="admin-btn" type="submit">Save Genre</button>
                </div>
            </form>
        </div>
    </section>
</x-admin-layout>
