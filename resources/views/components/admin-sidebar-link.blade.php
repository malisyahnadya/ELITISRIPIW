@props([
    'route',
    'pattern',
    'icon',
    'label',
])

<a
    href="{{ route($route) }}"
    @class([
        'admin-sidebar-link',
        'is-active' => request()->routeIs($pattern),
    ])
>
    <span class="admin-sidebar-icon"><i class="bi {{ $icon }}"></i></span>
    <span class="admin-sidebar-label">{{ $label }}</span>
</a>
