@if (session('success'))
    <div class="admin-flash success">{{ session('success') }}</div>
@endif

@if (session('error'))
    <div class="admin-flash error">{{ session('error') }}</div>
@endif

@if ($errors->any())
    <div class="admin-flash error">
        <strong>Periksa lagi data form.</strong>
        <ul style="margin: .45rem 0 0 1rem; padding: 0;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
