<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ __('app_name') }} — {{ __('hero_title') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

        <!-- Styles / Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

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
    </head>
    <body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] dark:text-[#EDEDEC] min-h-screen flex flex-col font-sans transition-colors duration-200 antialiased selection:bg-black selection:text-white dark:selection:bg-white dark:selection:text-black">

        <!-- Minimalist Navigation Bar -->
        <header class="sticky top-0 z-40 w-full backdrop-blur-md bg-[#FDFDFC]/90 dark:bg-[#0a0a0a]/90 border-b border-gray-200/80 dark:border-gray-800/80">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between gap-4">
                <div class="flex items-center gap-8">
                    <a href="{{ route('home') }}" class="font-semibold text-lg tracking-tight text-gray-900 dark:text-white hover:opacity-90 transition">
                        Usta<span class="text-accent">Top</span>
                    </a>

                    <nav class="hidden md:flex items-center space-x-6 text-xs font-medium text-gray-500 dark:text-gray-400">
                        <a href="#services" class="hover:text-accent dark:hover:text-accent transition duration-200">{{ __('categories_title') }}</a>
                        <a href="#masters" class="hover:text-accent dark:hover:text-accent transition duration-200">{{ __('top_masters_title') }}</a>
                        <a href="#how-it-works" class="hover:text-accent dark:hover:text-accent transition duration-200">{{ __('how_it_works_title') }}</a>
                        <a href="#faq" class="hover:text-accent dark:hover:text-accent transition duration-200">FAQ</a>
                    </nav>
                </div>

                <div class="flex items-center gap-2.5">
                    <!-- Minimalist Language Switcher -->
                    <x-language-switcher />

                    <!-- Theme Toggle -->
                    <button onclick="toggleTheme()" id="theme-toggle" type="button" aria-label="Toggle Theme"
                        class="p-1.5 rounded border border-gray-200 dark:border-gray-800 hover:border-accent transition duration-200">
                        <div class="dark:bg-white bg-black duration-200 rounded-full w-3.5 h-3.5"></div>
                    </button>

                    @auth
                        <a href="{{ route('dashboard') }}"
                            class="px-3 py-1.5 text-xs font-medium rounded border border-accent/40 bg-accent/10 text-accent hover:bg-accent hover:text-white dark:hover:text-black hover:shadow-[0_0_15px_rgba(14,165,233,0.3)] transition duration-200">
                            {{ __('nav_dashboard') }}
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                            class="px-3 py-1.5 text-xs font-medium text-gray-600 dark:text-gray-300 hover:text-accent dark:hover:text-accent transition duration-200">
                            {{ __('nav_login') }}
                        </a>
                        <a href="{{ route('register') }}"
                            class="px-3 py-1.5 text-xs font-medium rounded border border-accent/40 bg-accent/10 text-accent hover:bg-accent hover:text-white dark:hover:text-black hover:shadow-[0_0_15px_rgba(14,165,233,0.3)] transition duration-200">
                            {{ __('nav_register') }}
                        </a>
                    @endauth
                </div>
            </div>
        </header>

        <!-- Main Content (With Cyan Hover Effect & Sleek Buttons) -->
        <main class="flex-1">

            <!-- Hero Section -->
            <section class="py-16 lg:py-24 border-b border-gray-100 dark:border-gray-900">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">

                        <!-- Left Text & Search -->
                        <div class="lg:col-span-7 space-y-6 text-center lg:text-left">
                            <div class="inline-flex items-center gap-2 px-3 py-1 rounded text-[11px] font-medium border border-accent/30 bg-accent/10 text-accent">
                                <span class="w-1.5 h-1.5 rounded-full bg-accent animate-ping"></span>
                                <span>{{ __('live_badge') }}</span>
                            </div>

                            <h1 class="text-3xl sm:text-5xl font-bold tracking-tight text-gray-900 dark:text-white leading-tight">
                                {{ __('hero_title') }} <br>
                                <span class="text-gray-500 dark:text-gray-400 font-normal">- <span class="text-accent">{{ __('hero_subtitle') }}</span></span>
                            </h1>

                            <p class="text-sm sm:text-base text-gray-600 dark:text-gray-400 max-w-xl mx-auto lg:mx-0 leading-relaxed">
                                {{ __('hero_description') }}
                            </p>

                            <!-- Minimal Search Bar with Cyan Focus -->
                            <div class="pt-1 max-w-lg mx-auto lg:mx-0">
                                <form action="{{ route('service-requests.create') }}" method="GET" class="relative flex items-center">
                                    <input type="text" name="query" placeholder="{{ __('search_placeholder') }}"
                                        class="w-full pl-3 pr-28 py-2.5 text-xs rounded border border-gray-300 dark:border-gray-700 bg-transparent text-gray-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-accent focus:border-accent transition duration-200">
                                    <button type="submit"
                                        class="absolute right-1 px-3 py-1.5 text-xs font-medium rounded border border-accent/40 bg-accent/10 text-accent hover:bg-accent hover:text-white dark:hover:text-black hover:shadow-[0_0_15px_rgba(14,165,233,0.3)] transition duration-200">
                                        {{ __('find_master_btn') }}
                                    </button>
                                </form>
                            </div>

                            <!-- Sleek CTA Buttons -->
                            <div class="flex flex-wrap items-center justify-center lg:justify-start gap-3 pt-2">
                                <a href="{{ route('service-requests.create') }}"
                                    class="px-4 py-2 text-xs font-semibold rounded border border-accent/40 bg-accent/10 text-accent hover:bg-accent hover:text-white dark:hover:text-black hover:shadow-[0_0_20px_rgba(14,165,233,0.35)] transition duration-200">
                                    {{ __('find_master_btn') }}
                                </a>
                                <a href="{{ route('provider-requests.index') }}"
                                    class="px-4 py-2 text-xs font-medium rounded border border-gray-300 dark:border-gray-700 hover:border-accent hover:text-accent dark:hover:border-accent dark:hover:text-accent transition duration-200">
                                    {{ __('become_master_btn') }}
                                </a>
                            </div>

                            <!-- Trust Metrics -->
                            <div class="flex items-center justify-center lg:justify-start gap-5 pt-3 text-[11px] text-gray-400">
                                <div>&bull; {{ __('Tasdiqlangan ustalar') }}</div>
                                <div>&bull; {{ __('Telegram bildirishnomalar') }}</div>
                                <div>&bull; {{ __('0% mijoz komissiyasi') }}</div>
                            </div>
                        </div>

                        <!-- Right Visual Artwork -->
                        <div class="lg:col-span-5 relative flex items-center justify-center">
                            <div class="relative w-full max-w-sm">
                                <img src="{{ asset('img/usta.png') }}" alt="UstaTop"
                                    class="w-full h-auto rounded-lg">
                            </div>
                        </div>

                    </div>
                </div>
            </section>

            <!-- Live Numbers / Stats Section -->
            <section class="py-8 bg-gray-50/50 dark:bg-[#111110] border-b border-gray-100 dark:border-gray-900">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center text-xs">
                        <div>
                            <div class="text-2xl font-bold text-gray-900 dark:text-white font-mono">
                                {{ number_format($stats['completed_orders']) }}+
                            </div>
                            <div class="mt-0.5 text-gray-400">{{ __('stats_orders') }}</div>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-gray-900 dark:text-white font-mono">
                                {{ $stats['active_masters'] }}+
                            </div>
                            <div class="mt-0.5 text-gray-400">{{ __('stats_masters') }}</div>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-gray-900 dark:text-white font-mono">
                                {{ $stats['rating'] }}
                            </div>
                            <div class="mt-0.5 text-gray-400">{{ __('stats_rating') }}</div>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-gray-900 dark:text-white font-mono">
                                Realtime
                            </div>
                            <div class="mt-0.5 text-gray-400">{{ __('stats_telegram') }}</div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Categories Grid -->
            <section id="services" class="py-14 border-b border-gray-100 dark:border-gray-900">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="mb-8">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white tracking-tight">
                            {{ __('categories_title') }}
                        </h2>
                        <p class="text-xs text-gray-500 mt-0.5">
                            {{ __('categories_subtitle') }}
                        </p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach ($categories as $cat)
                            <a href="{{ route('service-requests.create') }}?category_id={{ $cat->id }}"
                                class="group p-4 rounded-lg border border-gray-200 dark:border-gray-800 bg-white dark:bg-[#111110] hover:border-accent hover:shadow-[0_0_20px_rgba(14,165,233,0.15)] transition duration-200 block">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-xs font-semibold text-gray-900 dark:text-white group-hover:text-accent transition duration-200">
                                        {{ $cat->name }}
                                    </h3>
                                    <span class="text-[10px] text-gray-400 font-mono group-hover:text-accent transition duration-200">
                                        {{ $cat->users_count > 0 ? $cat->users_count . ' ' . (app()->getLocale() === 'en' ? 'craftsmen' : (app()->getLocale() === 'ru' ? 'мастеров' : 'usta')) : (app()->getLocale() === 'en' ? 'Active' : (app()->getLocale() === 'ru' ? 'Активно' : 'Faol')) }}
                                    </span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </section>

            <!-- Featured Masters Showcase -->
            <section id="masters" class="py-14 border-b border-gray-100 dark:border-gray-900">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex items-center justify-between mb-8">
                        <div>
                            <h2 class="text-xl font-bold text-gray-900 dark:text-white tracking-tight">
                                {{ __('top_masters_title') }}
                            </h2>
                            <p class="text-xs text-gray-500 mt-0.5">
                                {{ __('top_masters_subtitle') }}
                            </p>
                        </div>
                        <a href="{{ route('service-requests.create') }}" class="text-xs text-gray-500 hover:text-accent dark:hover:text-accent transition duration-200">
                            {{ __('view_all') }} &rarr;
                        </a>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        @forelse ($masters as $master)
                            <div class="p-4 rounded-lg border border-gray-200 dark:border-gray-800 bg-white dark:bg-[#111110] hover:border-accent hover:shadow-[0_0_20px_rgba(14,165,233,0.15)] transition duration-200 flex flex-col justify-between">
                                <div>
                                    <div class="flex items-center justify-between">
                                        <div class="text-xs font-semibold text-gray-900 dark:text-white">
                                            {{ $master->name }}
                                        </div>
                                        <span class="text-[10px] text-accent font-mono font-medium">5.0 / 5.0</span>
                                    </div>

                                    <div class="mt-2 flex flex-wrap gap-1">
                                        @foreach ($master->categories as $cat)
                                            <span class="text-[10px] px-1.5 py-0.5 rounded border border-gray-100 dark:border-gray-800 text-gray-600 dark:text-gray-400">
                                                {{ $cat->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="mt-4 pt-3 border-t border-gray-100 dark:border-gray-800 flex items-center justify-between">
                                    <span class="text-[11px] text-gray-400">Uzbekistan</span>
                                    <a href="{{ route('service-requests.create') }}"
                                        class="px-2.5 py-1 text-xs rounded border border-gray-300 dark:border-gray-700 hover:border-accent hover:text-accent dark:hover:border-accent dark:hover:text-accent transition duration-200">
                                        {{ __('find_master_btn') }}
                                    </a>
                                </div>
                            </div>
                        @empty
                            <p class="text-xs text-gray-500 col-span-3 text-center py-6">
                                {{ __('no_orders') }}
                            </p>
                        @endforelse
                    </div>
                </div>
            </section>

            <!-- How It Works Step-by-Step -->
            <section id="how-it-works" class="py-14 border-b border-gray-100 dark:border-gray-900">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="mb-8 text-center max-w-xl mx-auto">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white tracking-tight">
                            {{ __('how_it_works_title') }}
                        </h2>
                        <p class="text-xs text-gray-500 mt-0.5">
                            {{ __('how_it_works_subtitle') }}
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="p-5 rounded-lg border border-gray-200 dark:border-gray-800 bg-white dark:bg-[#111110] hover:border-accent hover:shadow-[0_0_20px_rgba(14,165,233,0.12)] transition duration-200">
                            <div class="text-xs font-mono text-accent mb-2">01</div>
                            <h3 class="text-xs font-semibold text-gray-900 dark:text-white mb-1">{{ __('step_1_title') }}</h3>
                            <p class="text-xs text-gray-500 leading-relaxed">{{ __('step_1_desc') }}</p>
                        </div>

                        <div class="p-5 rounded-lg border border-gray-200 dark:border-gray-800 bg-white dark:bg-[#111110] hover:border-accent hover:shadow-[0_0_20px_rgba(14,165,233,0.12)] transition duration-200">
                            <div class="text-xs font-mono text-accent mb-2">02</div>
                            <h3 class="text-xs font-semibold text-gray-900 dark:text-white mb-1">{{ __('step_2_title') }}</h3>
                            <p class="text-xs text-gray-500 leading-relaxed">{{ __('step_2_desc') }}</p>
                        </div>

                        <div class="p-5 rounded-lg border border-gray-200 dark:border-gray-800 bg-white dark:bg-[#111110] hover:border-accent hover:shadow-[0_0_20px_rgba(14,165,233,0.12)] transition duration-200">
                            <div class="text-xs font-mono text-accent mb-2">03</div>
                            <h3 class="text-xs font-semibold text-gray-900 dark:text-white mb-1">{{ __('step_3_title') }}</h3>
                            <p class="text-xs text-gray-500 leading-relaxed">{{ __('step_3_desc') }}</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Telegram Bot Callout Banner -->
            <section class="py-8 bg-gray-50/50 dark:bg-[#111110] border-b border-gray-100 dark:border-gray-900">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                        <div class="text-center sm:text-left">
                            <h3 class="text-sm font-semibold text-gray-900 dark:text-white">
                                Telegram Bot &bull; <span class="text-accent">@ustatpuzbot</span>
                            </h3>
                            <p class="text-xs text-gray-500 mt-0.5">
                                Buyurtma holati va yangiliklarni to'g'ridan-to'g'ri Telegram orqali qabul qiling.
                            </p>
                        </div>
                        <a href="https://t.me/ustatpuzbot" target="_blank"
                            class="px-4 py-2 rounded text-xs font-medium border border-accent/40 bg-accent/10 text-accent hover:bg-accent hover:text-white dark:hover:text-black hover:shadow-[0_0_15px_rgba(14,165,233,0.3)] transition duration-200 shrink-0">
                            @ustatpuzbot &rarr;
                        </a>
                    </div>
                </div>
            </section>

            <!-- FAQ Section -->
            <section id="faq" class="py-14 border-b border-gray-100 dark:border-gray-900" x-data="{ active: 1 }">
                <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="mb-8 text-center">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white tracking-tight">
                            {{ __('faq_title') }}
                        </h2>
                    </div>

                    <div class="space-y-2">
                        <div class="border border-gray-200 dark:border-gray-800 rounded bg-white dark:bg-[#111110] hover:border-accent/60 transition duration-200">
                            <button @click="active = (active === 1 ? 0 : 1)"
                                class="w-full p-4 text-left text-xs font-medium text-gray-900 dark:text-white flex items-center justify-between">
                                <span>{{ __('faq_q1') }}</span>
                                <span class="font-mono text-gray-400" x-text="active === 1 ? '−' : '+'"></span>
                            </button>
                            <div x-show="active === 1" class="px-4 pb-4 text-xs text-gray-500 leading-relaxed">
                                {{ __('faq_a1') }}
                            </div>
                        </div>

                        <div class="border border-gray-200 dark:border-gray-800 rounded bg-white dark:bg-[#111110] hover:border-accent/60 transition duration-200">
                            <button @click="active = (active === 2 ? 0 : 2)"
                                class="w-full p-4 text-left text-xs font-medium text-gray-900 dark:text-white flex items-center justify-between">
                                <span>{{ __('faq_q2') }}</span>
                                <span class="font-mono text-gray-400" x-text="active === 2 ? '−' : '+'"></span>
                            </button>
                            <div x-show="active === 2" class="px-4 pb-4 text-xs text-gray-500 leading-relaxed" style="display: none;">
                                {{ __('faq_a2') }}
                            </div>
                        </div>

                        <div class="border border-gray-200 dark:border-gray-800 rounded bg-white dark:bg-[#111110] hover:border-accent/60 transition duration-200">
                            <button @click="active = (active === 3 ? 0 : 3)"
                                class="w-full p-4 text-left text-xs font-medium text-gray-900 dark:text-white flex items-center justify-between">
                                <span>{{ __('faq_q3') }}</span>
                                <span class="font-mono text-gray-400" x-text="active === 3 ? '−' : '+'"></span>
                            </button>
                            <div x-show="active === 3" class="px-4 pb-4 text-xs text-gray-500 leading-relaxed" style="display: none;">
                                {{ __('faq_a3') }}
                            </div>
                        </div>
                    </div>
                </div>
            </section>

        </main>

        <!-- Minimalist Footer with Accent -->
        <footer class="py-8 bg-white dark:bg-[#0a0a0a] border-t border-gray-100 dark:border-gray-900 text-xs text-gray-400">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div>
                    <span class="font-semibold text-gray-900 dark:text-white tracking-tight">Usta<span class="text-accent">Top</span></span> &bull; &copy; {{ date('Y') }} {{ __('footer_rights') }}
                </div>

                <div class="flex items-center gap-4">
                    <a href="#services" class="hover:text-accent dark:hover:text-accent transition duration-200">{{ __('categories_title') }}</a>
                    <a href="#how-it-works" class="hover:text-accent dark:hover:text-accent transition duration-200">{{ __('how_it_works_title') }}</a>
                    <a href="https://t.me/ustatpuzbot" target="_blank" class="hover:text-accent dark:hover:text-accent transition duration-200">@ustatpuzbot</a>
                </div>
            </div>
        </footer>

        <script>
            window.AUTHENTICATED = @json(auth()->check());
            window.USER_THEME = @json(auth()->user()->theme ?? null);
        </script>
    </body>
</html>
