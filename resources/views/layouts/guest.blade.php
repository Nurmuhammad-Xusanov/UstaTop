<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'UstaTop') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <script>
            (function() {
                var theme = null;
                try { theme = localStorage.getItem('theme'); } catch(e){}
                if (!theme || theme === 'system') {
                    theme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
                }
                if (theme === 'dark') {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
            })();
        </script>
    </head>
    <body class="font-sans text-gray-900 dark:text-gray-100 bg-[#FDFDFC] dark:bg-[#0a0a0a] min-h-screen flex flex-col justify-between antialiased transition-colors duration-200">

        <!-- Guest Minimal Header -->
        <header class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <a href="/" class="font-bold text-lg tracking-tight text-gray-900 dark:text-white hover:opacity-90 transition">
                Usta<span class="text-accent">Top</span>
            </a>

            <div class="flex items-center gap-2">
                <button onclick="toggleTheme()" id="theme-toggle" type="button" aria-label="Toggle Theme"
                    class="p-1.5 rounded border border-gray-200 dark:border-gray-800 hover:border-accent transition duration-200">
                    <div class="dark:bg-white bg-black duration-200 rounded-full w-3.5 h-3.5"></div>
                </button>
            </div>
        </header>

        <!-- Auth Card Container -->
        <div class="flex-1 flex flex-col justify-center items-center px-4 py-8">
            <div class="w-full sm:max-w-sm bg-white dark:bg-[#111110] border border-gray-200 dark:border-gray-800 rounded-xl p-6 sm:p-8 shadow-sm">
                {{ $slot }}
            </div>
        </div>

        <!-- Guest Footer -->
        <footer class="py-6 text-center text-xs text-gray-400">
            &copy; {{ date('Y') }} UstaTop. {{ __('footer_rights') }}
        </footer>

        <script>
            window.AUTHENTICATED = @json(auth()->check());
            window.USER_THEME = @json(auth()->user()->theme ?? null);
        </script>
    </body>
</html>
