@props([
    'title' => 'Admin',
    'subtitle' => null,
    'eyebrow' => null,
])

<x-app-layout>
    <div class="admin-page">

        <div class="admin-shell">
            <x-admin-sidebar />

            <main class="admin-main">
                <div class="admin-page-head">
                    <div>
                        <span class="admin-page-eyebrow">{{ $eyebrow ?? $title }}</span>
                        <h1>{{ $title }}</h1>
                        @if($subtitle)
                            <p>{{ $subtitle }}</p>
                        @endif
                    </div>
                    @isset($actions)
                        <div class="admin-actions">{{ $actions }}</div>
                    @endisset
                </div>

                @if(session('success'))
                    <div class="admin-alert admin-alert-success">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="admin-alert admin-alert-error">{{ session('error') }}</div>
                @endif

                {{ $slot }}
            </main>
        </div>
    </div>
</x-app-layout>
