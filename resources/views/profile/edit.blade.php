<x-app-layout>
    <div class="elit-page pb-20">
        <div class="elit-shell py-10">
            {{-- Flash message --}}
            @if(session('success'))
                <div class="mb-5 rounded-2xl border border-emerald-300/40 bg-emerald-500/15 px-5 py-3 text-sm font-bold text-emerald-100 flex items-center gap-2">
                    <i class="bi bi-check-circle"></i>
                    {{ session('success') }}
                </div>
            @endif

            <a href="{{ route('profile.index') }}" class="elit-ghost-btn mb-6 inline-flex gap-2">
                <i class="bi bi-arrow-left"></i> Back to Profile
            </a>
            <section class="elit-panel rounded-2xl p-6 lg:p-10">
                <h1 class="text-3xl font-black text-white">Edit Profile</h1>
                <p class="mt-2 text-sm font-semibold text-violet-100/75">Update name, username, email, bio, dan foto profil.</p>

                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="mt-8 grid gap-5 md:grid-cols-2">
                    @csrf
                    @method('PATCH')
                    <div>
                        <label for="name" class="mb-1 block text-sm font-black text-white">Name</label>
                        <input id="name" name="name" value="{{ old('name', $user->name) }}" maxlength="250" class="elit-input px-5 py-2.5 text-sm" required>
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>
                    <div>
                        <label for="username" class="mb-1 block text-sm font-black text-white">Username</label>
                        <input id="username" name="username" value="{{ old('username', $user->username) }}" maxlength="30" class="elit-input px-5 py-2.5 text-sm" required>
                        <x-input-error :messages="$errors->get('username')" class="mt-2" />
                    </div>
                    <div>
                        <label for="email" class="mb-1 block text-sm font-black text-white">Email</label>
                        <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" class="elit-input px-5 py-2.5 text-sm" required>
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>
                    <div>
                        <label for="profile_photo" class="mb-1 block text-sm font-black text-white">Profile Photo</label>
                        <input id="profile_photo" name="profile_photo" type="file" accept="image/*" class="block w-full text-sm text-violet-100 file:mr-4 file:rounded-full file:border-0 file:bg-violet-200 file:px-4 file:py-2 file:text-sm file:font-black file:text-[#2b1d46]">
                        <x-input-error :messages="$errors->get('profile_photo')" class="mt-2" />

                        @if($user->profile_photo)
                            <button
                                type="submit"
                                form="delete-profile-photo-form"
                                class="elit-ghost-btn mt-3 gap-2 text-xs text-red-100 hover:bg-red-500/20"
                            >
                                <i class="bi bi-trash"></i>
                                Hapus Foto Profil
                            </button>
                        @endif
                    </div>
                    <div class="md:col-span-2">
                        <label for="bio" class="mb-1 block text-sm font-black text-white">Bio</label>
                        <textarea id="bio" name="bio" rows="5" class="elit-textarea px-5 py-4 text-sm">{{ old('bio', $user->bio) }}</textarea>
                        <x-input-error :messages="$errors->get('bio')" class="mt-2" />
                    </div>
                    <div class="flex justify-end md:col-span-2">
                        <button type="submit" class="elit-btn">Save Change</button>
                    </div>
                </form>

                @if($user->profile_photo)
                    <form
                        id="delete-profile-photo-form"
                        method="POST"
                        action="{{ route('profile.photo.destroy') }}"
                        onsubmit="return confirm('Hapus foto profil saat ini?')"
                    >
                        @csrf
                        @method('DELETE')
                    </form>
                @endif
                {{-- ═══════════════════════════════════════════
                     SECTION: GANTI PASSWORD
                     Route: PUT profile.password.update
                     ═══════════════════════════════════════════ --}}
            <section class="elit-panel mt-6 rounded-2xl p-6 lg:p-10">
                <div class="flex items-start gap-3 mb-6">
                    <div class="flex-shrink-0 w-9 h-9 rounded-full bg-violet-300/10 flex items-center justify-center">
                        <i class="bi bi-lock text-violet-300/70"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-black text-white">Ganti Password</h2>
                        <p class="mt-0.5 text-sm text-violet-200/60 font-medium">
                            Pastikan kamu mengingat password baru setelah disimpan.
                        </p>
                    </div>
                </div>

                <form method="POST" action="{{ route('profile.password.update') }}" class="grid gap-5 md:grid-cols-2">
                    @csrf
                    @method('PUT')

                    {{-- Password saat ini --}}
                    <div class="md:col-span-2">
                        <label for="current_password" class="mb-1 block text-sm font-black text-white">
                            Password Saat Ini
                            <span class="text-violet-300/50 font-normal ml-1">(wajib diisi)</span>
                        </label>
                        <input
                            id="current_password"
                            name="current_password"
                            type="password"
                            class="elit-input px-5 py-2.5 text-sm"
                            autocomplete="current-password"
                            placeholder="Masukkan password lama kamu"
                            required>
                        <x-input-error :messages="$errors->get('current_password')" class="mt-2" />
                    </div>

                    {{-- Password baru --}}
                    <div>
                        <label for="password" class="mb-1 block text-sm font-black text-white">
                            Password Baru
                        </label>
                        <input
                            id="password"
                            name="password"
                            type="password"
                            class="elit-input px-5 py-2.5 text-sm"
                            autocomplete="new-password"
                            placeholder="Min. 8 karakter"
                            required>
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    {{-- Konfirmasi password baru --}}
                    <div>
                        <label for="password_confirmation" class="mb-1 block text-sm font-black text-white">
                            Konfirmasi Password Baru
                        </label>
                        <input
                            id="password_confirmation"
                            name="password_confirmation"
                            type="password"
                            class="elit-input px-5 py-2.5 text-sm"
                            autocomplete="new-password"
                            placeholder="Ulangi password baru"
                            required>
                        {{-- Tidak perlu x-input-error karena Laravel merge ke 'password' --}}
                    </div>

                    {{-- Tombol simpan --}}
                    <div class="flex justify-end md:col-span-2 border-t border-violet-200/10 pt-5">
                        <button type="submit" class="elit-btn gap-2 px-6">
                            <i class="bi bi-shield-lock"></i>
                            Update Password
                        </button>
                    </div>
                </form>
            </section>

        </div>
    </div>
</x-app-layout>

