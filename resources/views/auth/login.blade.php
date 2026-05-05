<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="elit-panel rounded-3xl border border-white/10 px-3 py-3 sm:px-6 sm:py-6 lg:px-8">
                <!-- Email Address -->
            <div>
            <x-input-label for="email" :value="__('Email')" class="text-sm font-semibold text-white" />
            <x-text-input id="email" class="elit-input mt-1 px-4 py-3 text-sm placeholder-white/60" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-rose-200" />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-input-label for="password" :value="__('Password')" class="text-sm font-semibold text-white" />

                <x-text-input id="password" class="elit-input mt-1 px-4 py-3 text-sm placeholder-white/60"
                                type="password"
                                name="password"
                                required autocomplete="current-password" />

                <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-rose-200" />
            </div>

            <!-- Remember Me -->
            <div class="block mt-4">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox" class="h-4 w-4 rounded border-white/40 bg-white/10 text-white focus:ring-2 focus:ring-white/60" name="remember">
                    <span class="ms-2 text-sm text-white/70">{{ __('Remember me') }}</span>
                </label>
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-primary-button class="elit-btn ms-3">
                    {{ __('Log in') }}
                </x-primary-button>
            </div>
        </div>

    </form>
</x-guest-layout>
