<x-guest-layout>
    <div class="mb-6">
        <h1 class="text-xl font-bold tracking-tight text-gray-900 dark:text-white">
            {{ __('register_title') }}
        </h1>
        <p class="text-xs text-gray-500 mt-1">
            {{ __('register_subtitle') }}
        </p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <!-- Name -->
        <div>
            <label for="name" class="block text-xs font-medium text-gray-700 dark:text-gray-300">
                {{ __('field_name') }}
            </label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                class="mt-1 block w-full px-3 py-2 text-xs rounded-md border border-gray-300 dark:border-gray-700 bg-transparent text-gray-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-black dark:focus:ring-white focus:border-black dark:focus:border-white transition" />
            <x-input-error :messages="$errors->get('name')" class="mt-1" />
        </div>

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-xs font-medium text-gray-700 dark:text-gray-300">
                {{ __('field_email') }}
            </label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                class="mt-1 block w-full px-3 py-2 text-xs rounded-md border border-gray-300 dark:border-gray-700 bg-transparent text-gray-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-black dark:focus:ring-white focus:border-black dark:focus:border-white transition" />
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-xs font-medium text-gray-700 dark:text-gray-300">
                {{ __('field_password') }}
            </label>
            <input id="password" type="password" name="password" required autocomplete="new-password"
                class="mt-1 block w-full px-3 py-2 text-xs rounded-md border border-gray-300 dark:border-gray-700 bg-transparent text-gray-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-black dark:focus:ring-white focus:border-black dark:focus:border-white transition" />
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="block text-xs font-medium text-gray-700 dark:text-gray-300">
                {{ __('field_confirm_password') }}
            </label>
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                class="mt-1 block w-full px-3 py-2 text-xs rounded-md border border-gray-300 dark:border-gray-700 bg-transparent text-gray-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-black dark:focus:ring-white focus:border-black dark:focus:border-white transition" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
        </div>

        <!-- Submit Button -->
        <div class="pt-2">
            <button type="submit"
                class="w-full py-2.5 px-4 text-xs font-semibold rounded-md bg-black text-white dark:bg-white dark:text-black hover:opacity-85 transition tracking-wide shadow-sm">
                {{ __('nav_register') }}
            </button>
        </div>

        <!-- Link to Login -->
        <div class="text-center pt-2 border-t border-gray-100 dark:border-gray-800">
            <a href="{{ route('login') }}"
                class="text-xs text-gray-500 hover:text-black dark:hover:text-white transition">
                {{ __('already_registered') }}
            </a>
        </div>
    </form>
</x-guest-layout>
