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

        <!-- Theme Initialization (Zero Flicker & Cross-Device Sync) -->
        <script>
            (function() {
                var userTheme = @json(auth()->user()->theme ?? null);
                var syncTheme = @json(auth()->user()->sync_theme ?? true);
                var theme = null;

                if (userTheme && syncTheme && userTheme !== 'system') {
                    theme = userTheme;
                } else {
                    try { theme = localStorage.getItem('theme'); } catch(e){}
                }

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

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-[#FDFDFC] dark:bg-black text-gray-900 dark:text-gray-100 transition-colors duration-200">
        <div class="min-h-screen flex flex-col">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white dark:bg-[#0d0d0c] border-b border-gray-100 dark:border-gray-800">
                    <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main class="flex-1">
                {{ $slot }}
            </main>
        </div>

        <script>
            window.AUTHENTICATED = @json(auth()->check());
            window.USER_THEME = @json(auth()->user()->theme ?? null);
        </script>
    </body>
</html>
