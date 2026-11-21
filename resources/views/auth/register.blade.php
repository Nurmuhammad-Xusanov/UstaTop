<x-guest-layout>
    <div class="mb-6">
        <h1 class="text-xl font-bold tracking-tight text-gray-900 dark:text-white">
            {{ __('register_title') }}
        </h1>
        <p class="text-xs text-gray-500 mt-1">
            {{ __('register_subtitle') }}
        </p>
    </div>

    <!-- Google OAuth Button -->
    <div class="mb-5">
        <a href="{{ route('auth.google') }}"
            class="w-full flex items-center justify-center gap-3 py-2.5 px-4 text-xs font-medium rounded-md border border-gray-300 dark:border-gray-700 bg-white dark:bg-[#161615] text-gray-700 dark:text-gray-200 hover:border-accent hover:text-accent dark:hover:border-accent dark:hover:text-accent hover:shadow-[0_0_15px_rgba(14,165,233,0.2)] transition duration-200">
            <svg class="w-4 h-4" viewBox="0 0 24 24">
                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
            </svg>
            <span>{{ __('Google orqali ro\'yxatdan o\'tish') }}</span>
        </a>
    </div>

    <!-- Divider -->
    <div class="relative flex items-center justify-center mb-5">
        <div class="border-t border-gray-200 dark:border-gray-800 w-full"></div>
        <span class="bg-white dark:bg-[#111110] px-3 text-[11px] text-gray-400 uppercase tracking-wider font-mono absolute">{{ __('yoki') }}</span>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <!-- Name -->
        <div>
            <label for="name" class="block text-xs font-medium text-gray-700 dark:text-gray-300">
                {{ __('field_name') }}
            </label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                class="mt-1 block w-full px-3 py-2 text-xs rounded-md border border-gray-300 dark:border-gray-700 bg-transparent text-gray-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-accent focus:border-accent transition duration-200" />
            <x-input-error :messages="$errors->get('name')" class="mt-1" />
        </div>

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-xs font-medium text-gray-700 dark:text-gray-300">
                {{ __('field_email') }}
            </label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                class="mt-1 block w-full px-3 py-2 text-xs rounded-md border border-gray-300 dark:border-gray-700 bg-transparent text-gray-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-accent focus:border-accent transition duration-200" />
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-xs font-medium text-gray-700 dark:text-gray-300">
                {{ __('field_password') }}
            </label>
            <input id="password" type="password" name="password" required autocomplete="new-password"
                class="mt-1 block w-full px-3 py-2 text-xs rounded-md border border-gray-300 dark:border-gray-700 bg-transparent text-gray-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-accent focus:border-accent transition duration-200" />
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="block text-xs font-medium text-gray-700 dark:text-gray-300">
                {{ __('field_confirm_password') }}
            </label>
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                class="mt-1 block w-full px-3 py-2 text-xs rounded-md border border-gray-300 dark:border-gray-700 bg-transparent text-gray-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-accent focus:border-accent transition duration-200" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
        </div>

        <!-- Submit Button -->
        <div class="pt-2">
            <button type="submit"
                class="w-full py-2.5 px-4 text-xs font-semibold rounded-md border border-accent/40 bg-accent/10 text-accent hover:bg-accent hover:text-white dark:hover:text-black hover:shadow-[0_0_20px_rgba(14,165,233,0.35)] transition duration-200 tracking-wide">
                {{ __('nav_register') }}
            </button>
        </div>

        <!-- Link to Login -->
        <div class="text-center pt-2 border-t border-gray-100 dark:border-gray-800">
            <a href="{{ route('login') }}"
                class="text-xs text-gray-500 hover:text-accent dark:hover:text-accent transition duration-200">
                {{ __('already_registered') }}
            </a>
        </div>
    </form>
</x-guest-layout>
