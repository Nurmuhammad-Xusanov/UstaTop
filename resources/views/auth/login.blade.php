<x-guest-layout>
    <div class="mb-6">
        <h1 class="text-xl font-bold tracking-tight text-gray-900 dark:text-white">
            {{ __('login_title') }}
        </h1>
        <p class="text-xs text-gray-500 mt-1">
            {{ __('login_subtitle') }}
        </p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-xs font-medium text-gray-700 dark:text-gray-300">
                {{ __('field_email') }}
            </label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                class="mt-1 block w-full px-3 py-2 text-xs rounded-md border border-gray-300 dark:border-gray-700 bg-transparent text-gray-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-black dark:focus:ring-white focus:border-black dark:focus:border-white transition" />
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <!-- Password -->
        <div>
            <div class="flex items-center justify-between">
                <label for="password" class="block text-xs font-medium text-gray-700 dark:text-gray-300">
                    {{ __('field_password') }}
                </label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}"
                        class="text-[11px] text-gray-500 hover:text-black dark:hover:text-white transition">
                        {{ __('forgot_password_link') }}
                    </a>
                @endif
            </div>

            <input id="password" type="password" name="password" required autocomplete="current-password"
                class="mt-1 block w-full px-3 py-2 text-xs rounded-md border border-gray-300 dark:border-gray-700 bg-transparent text-gray-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-black dark:focus:ring-white focus:border-black dark:focus:border-white transition" />
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center">
            <input id="remember_me" type="checkbox" name="remember"
                class="rounded border-gray-300 dark:border-gray-700 text-black dark:text-white focus:ring-black dark:focus:ring-white h-3.5 w-3.5 bg-transparent" />
            <label for="remember_me" class="ms-2 text-xs text-gray-600 dark:text-gray-400">
                {{ __('remember_me') }}
            </label>
        </div>

        <!-- Submit Button (Sleek Minimalist) -->
        <div class="pt-2">
            <button type="submit"
                class="w-full py-2.5 px-4 text-xs font-semibold rounded-md bg-black text-white dark:bg-white dark:text-black hover:opacity-85 transition tracking-wide shadow-sm">
                {{ __('nav_login') }}
            </button>
        </div>

        <!-- Link to Register -->
        <div class="text-center pt-2 border-t border-gray-100 dark:border-gray-800">
            <a href="{{ route('register') }}"
                class="text-xs text-gray-500 hover:text-black dark:hover:text-white transition">
                {{ __('no_account') }}
            </a>
        </div>
    </form>
</x-guest-layout>