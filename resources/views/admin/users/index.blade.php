<x-admin-layout title="Monitoring Akun User" subtitle="Pantau akun user. Admin hanya bisa mengubah role, bukan password atau data pribadi user.">
    <section class="admin-search-card">
        <form method="GET" action="{{ route('admin.users.index') }}" class="admin-filter-form">
            <input class="admin-input" name="search" value="{{ $search }}" placeholder="Search name, username, email">
            <select class="admin-select" name="sort">
                <option value="asc" @selected($sort === 'asc')>Name A-Z</option>
                <option value="desc" @selected($sort === 'desc')>Newest First</option>
            </select>
            <button class="admin-btn" type="submit"><i class="bi bi-search"></i> Search</button>
            @if($search)<a href="{{ route('admin.users.index') }}" class="admin-btn-outline">Reset</a>@endif
        </form>
        <span class="admin-badge">{{ $users->total() }} user</span>
    </section>

    <div style="height:1rem"></div>

    <section class="admin-panel">
        <header class="admin-panel-head">
            <h2>Users</h2>
            <span class="admin-badge">Edit role only</span>
        </header>
        <div class="admin-panel-body admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Reviews</th>
                        <th>Ratings</th>
                        <th>Watchlist</th>
                        <th>Joined</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>{{ $user->name ?: '-' }}</td>
                            <td>{{ $user->username }}</td>
                            <td>{{ $user->email }}</td>
                            <td><span class="admin-badge {{ $user->role === 'admin' ? 'admin-badge-green' : '' }}">{{ $user->role }}</span></td>
                            <td>{{ $user->reviews_count ?? 0 }}</td>
                            <td>{{ $user->ratings_count ?? 0 }}</td>
                            <td>{{ $user->watchlists_count ?? 0 }}</td>
                            <td>{{ optional($user->created_at)->format('Y-m-d') ?? '-' }}</td>
                            <td>
                                <a href="{{ route('admin.users.edit', $user) }}" class="admin-btn-soft">Edit Role</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="9" class="admin-empty">Belum ada user untuk dimonitor.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
    <div class="admin-pagination">{{ $users->links() }}</div>
</x-admin-layout>
