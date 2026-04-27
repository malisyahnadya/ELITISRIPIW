<x-app-layout>
    <div class="bg-slate-950 text-slate-100">
        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold">Profile</h1>
            <p class="mt-2 text-slate-300">Manage your account settings and preferences.</p>
        </div>
        <a href="{{ route('profile.edit') }}" class="ml-4 text-blue-500 hover:text-blue-700">Buat ke halaman edit profile</a>
        <h2>Perhatikan fungsi yang sudah ada di controller, dan routing di web.php</h2>
    </div>

</x-app-layout>