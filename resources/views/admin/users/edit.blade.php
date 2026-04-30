<x-admin-layout title="Edit Role User" subtitle="Admin hanya bisa mengubah role akun ini. Password dan data pribadi tidak dapat diubah dari menu admin.">
    <x-slot name="actions"><a href="{{ route('admin.users.index') }}" class="admin-btn-outline"><i class="bi bi-arrow-left"></i> Back</a></x-slot>

    <section class="admin-panel">
        <header class="admin-panel-head">
            <h2>{{ $user->name ?: $user->username }}</h2>
            <div class="admin-actions">
                <span class="admin-badge {{ $user->role === 'admin' ? 'admin-badge-green' : '' }}">{{ $user->role }}</span>
                <span class="admin-badge">{{ $user->reviews_count ?? 0 }} review</span>
                <span class="admin-badge">{{ $user->ratings_count ?? 0 }} rating</span>
            </div>
        </header>
        <div class="admin-panel-body">
            <div class="admin-readonly-grid">
                <div class="admin-readonly-item"><span>Name</span><strong>{{ $user->name ?: '-' }}</strong></div>
                <div class="admin-readonly-item"><span>Username</span><strong>{{ $user->username }}</strong></div>
                <div class="admin-readonly-item"><span>Email</span><strong>{{ $user->email }}</strong></div>
                <div class="admin-readonly-item"><span>Reviews</span><strong>{{ $user->reviews_count ?? 0 }}</strong></div>
                <div class="admin-readonly-item"><span>Ratings</span><strong>{{ $user->ratings_count ?? 0 }}</strong></div>
                <div class="admin-readonly-item"><span>Watchlist</span><strong>{{ $user->watchlists_count ?? 0 }}</strong></div>
            </div>

            <form id="user-role-update" method="POST" action="{{ route('admin.users.update', $user) }}" class="admin-form">
                @csrf
                @method('PUT')
                <div class="admin-form-grid">
                    <div class="admin-field">
                        <label for="role">Role</label>
                        <select id="role" class="admin-select" name="role" required>
                            <option value="user" @selected(old('role', $user->role) === 'user')>user</option>
                            <option value="admin" @selected(old('role', $user->role) === 'admin')>admin</option>
                        </select>
                        <p class="admin-help">Hanya field role yang bisa diedit dari halaman admin.</p>
                        <x-input-error :messages="$errors->get('role')" class="mt-2" />
                    </div>
                </div>
            </form>

            <div class="admin-actions" style="justify-content:flex-end;margin-top:1rem">
                <a href="{{ route('admin.users.index') }}" class="admin-btn-outline">Cancel</a>
                <button form="user-role-update" class="admin-btn" type="submit">Save Role</button>
            </div>
        </div>
    </section>
</x-admin-layout>
