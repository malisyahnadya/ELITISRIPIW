@php
    $adminLinks = [
        ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'active' => 'admin.dashboard'],
        ['label' => 'Movies', 'route' => 'admin.movies.index', 'active' => 'admin.movies.*'],
        ['label' => 'Actors', 'route' => 'admin.actors.index', 'active' => 'admin.actors.*'],
        ['label' => 'Genres', 'route' => 'admin.genres.index', 'active' => 'admin.genres.*'],
        ['label' => 'Directors', 'route' => 'admin.directors.index', 'active' => 'admin.directors.*'],
        ['label' => 'Reviews', 'route' => 'admin.reviews.index', 'active' => 'admin.reviews.*'],
        ['label' => 'Users', 'route' => 'admin.users.index', 'active' => 'admin.users.*'],
    ];
@endphp

<header class="admin-topbar">
    <a href="{{ route('admin.dashboard') }}" class="admin-brand">
        <i class="bi bi-list"></i>
        <span>ELITISRIPIW</span>
    </a>

    <form method="GET" action="{{ route('search') }}" class="admin-search">
        <button type="submit" aria-label="Search" style="background: transparent; border: 0; padding: 0; cursor: pointer;"><i class="bi bi-search"></i></button>
        <input type="search" name="q" value="{{ request('q') }}" placeholder="Enola Holmes">
    </form>

    <a href="{{ route('profile.index') }}" class="admin-user-dot" aria-label="Profile"><i class="bi bi-person-fill"></i></a>
</header>

<nav class="admin-menu" aria-label="Admin navigation">
    @foreach ($adminLinks as $item)
        <a href="{{ route($item['route']) }}" class="{{ request()->routeIs($item['active']) ? 'is-active' : '' }}">
            {{ $item['label'] }}
        </a>
    @endforeach
</nav>
