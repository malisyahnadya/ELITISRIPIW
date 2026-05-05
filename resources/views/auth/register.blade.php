
<x-guest-layout>
    <div class="elit-panel rounded-3xl border border-white/10 px-3 py-3 sm:px-6 sm:py-6 lg:px-8">
        <div class="mt-2 text-center">
            <h1 class="mt-1 text-2xl font-black text-white">Create account</h1>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <!-- Full Name -->
            <div class="space-y-1">
                <x-input-label for="name" :value="__('Name')" class="text-sm font-semibold text-white" />
                <x-text-input id="name" class="elit-input px-4 py-3 text-sm placeholder-white/60" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                <x-input-error :messages="$errors->get('name')" class="text-sm text-rose-200" />
            </div>

        <!-- Username -->
            <div class="space-y-1">
                <x-input-label for="username" :value="__('Username')" class="text-sm font-semibold text-white" />
                <x-text-input id="username" class="elit-input px-4 py-3 text-sm placeholder-white/60" type="text" name="username" :value="old('username')" required autocomplete="username" />
                <x-input-error :messages="$errors->get('username')" class="text-sm text-rose-200" />
            </div>

        <!-- Email Address -->
            <div class="space-y-1">
                <x-input-label for="email" :value="__('Email')" class="text-sm font-semibold text-white" />
                <x-text-input id="email" class="elit-input px-4 py-3 text-sm placeholder-white/60" type="email" name="email" :value="old('email')" required autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="text-sm text-rose-200" />
            </div>

        <!-- Password -->
            <div class="space-y-1">
                <x-input-label for="password" :value="__('Password')" class="text-sm font-semibold text-white" />
                <x-text-input id="password" class="elit-input px-4 py-3 text-sm placeholder-white/60"
                    type="password"
                    name="password"
                    required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password')" class="text-sm text-rose-200" />
            </div>

        <!-- Confirm Password -->
            <div class="space-y-1">
                <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-sm font-semibold text-white" />
                <x-text-input id="password_confirmation" class="elit-input px-4 py-3 text-sm placeholder-white/60"
                    type="password"
                    name="password_confirmation" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="text-sm text-rose-200" />
            </div>

            <div class="flex flex-col gap-3 pt-4 sm:flex-row sm:items-center sm:justify-between">
                <a class="elit-ghost-btn text-sm" href="{{ route('login') }}">
                    {{ __('Already registered?') }}
                </a>

                <x-primary-button class="elit-btn">
                    {{ __('Register') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</x-guest-layout>