@php
    $user = auth()->user();
    $adminName = $user?->name ?: ($user?->username ?: 'admin');
    $adminEmail = $user?->email ?: 'admin@demo.com';

    $menuItems = [
        ['route' => 'admin.dashboard', 'pattern' => 'admin.dashboard', 'label' => 'Dashboard', 'icon' => 'bi-grid-1x2'],
        ['route' => 'admin.movies.index', 'pattern' => 'admin.movies.*', 'label' => 'Movies', 'icon' => 'bi-camera-reels'],
        ['route' => 'admin.tmdb-import.index', 'pattern' => 'admin.tmdb-import.*', 'label' => 'Import TMDB', 'icon' => 'bi-cloud-download'],
        ['route' => 'admin.genres.index', 'pattern' => 'admin.genres.*', 'label' => 'Genres', 'icon' => 'bi-square'],
        ['route' => 'admin.actors.index', 'pattern' => 'admin.actors.*', 'label' => 'Actors', 'icon' => 'bi-people'],
        ['route' => 'admin.directors.index', 'pattern' => 'admin.directors.*', 'label' => 'Directors', 'icon' => 'bi-mortarboard'],
        ['route' => 'admin.reviews.index', 'pattern' => 'admin.reviews.*', 'label' => 'Reviews', 'icon' => 'bi-chat-square-text'],
        ['route' => 'admin.users.index', 'pattern' => 'admin.users.*', 'label' => 'Users', 'icon' => 'bi-people-fill'],
    ];
@endphp

<aside class="admin-sidebar" aria-label="Admin sidebar">
    <div class="admin-sidebar-inner">
        <a href="{{ route('admin.dashboard') }}" class="admin-brand-card">
            <span class="admin-brand-icon"><i class="bi bi-list"></i></span>
            <span>
                <strong>ELITISRIPIW</strong>
                <small>Admin Panel</small>
            </span>
        </a>

        <div class="admin-sidebar-section">
            <p class="admin-sidebar-section-label">Menu</p>
            <nav class="admin-sidebar-nav">
                @foreach($menuItems as $item)
                    <x-admin-sidebar-link
                        :route="$item['route']"
                        :pattern="$item['pattern']"
                        :icon="$item['icon']"
                        :label="$item['label']"
                    />
                @endforeach
            </nav>
        </div>

        <div class="admin-sidebar-section admin-sidebar-site-section">
            <p class="admin-sidebar-section-label">Site</p>
            <a href="{{ route('home') }}" class="admin-sidebar-link admin-site-link">
                <span class="admin-sidebar-icon"><i class="bi bi-chevron-left"></i></span>
                <span class="admin-sidebar-label">Back to Site</span>
            </a>
        </div>

        <div class="admin-user-card">
            <span>Logged in as</span>
            <strong>{{ $adminName }}</strong>
            <small>{{ $adminEmail }}</small>
        </div>
    </div>
</aside>