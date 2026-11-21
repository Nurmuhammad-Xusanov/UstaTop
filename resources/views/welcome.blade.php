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
            // Synchronous theme initialization to prevent flash
            if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        </script>
    </head>
    <body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] dark:text-[#EDEDEC] min-h-screen flex flex-col font-sans transition-colors duration-200 antialiased selection:bg-accent selection:text-white">

        <!-- Top Navigation -->
        <header class="sticky top-0 z-40 w-full backdrop-blur-md bg-[#FDFDFC]/85 dark:bg-[#0a0a0a]/85 border-b border-gray-100 dark:border-gray-800/80">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between gap-4">
                <div class="flex items-center gap-8">
                    <a href="{{ route('home') }}" class="flex items-center gap-2 group">
                        <span class="w-8 h-8 rounded-lg bg-black dark:bg-white text-white dark:text-black font-bold flex items-center justify-center text-base tracking-tight transition group-hover:scale-105">
                            U
                        </span>
                        <span class="font-bold text-xl tracking-tight text-gray-900 dark:text-white">
                            Usta<span class="text-accent">Top</span>
                        </span>
                    </a>

                    <nav class="hidden md:flex items-center space-x-6 text-sm font-medium text-gray-600 dark:text-gray-300">
                        <a href="#services" class="hover:text-accent dark:hover:text-accent transition">{{ __('categories_title') }}</a>
                        <a href="#masters" class="hover:text-accent dark:hover:text-accent transition">{{ __('top_masters_title') }}</a>
                        <a href="#how-it-works" class="hover:text-accent dark:hover:text-accent transition">{{ __('how_it_works_title') }}</a>
                        <a href="#faq" class="hover:text-accent dark:hover:text-accent transition">FAQ</a>
                    </nav>
                </div>

                <div class="flex items-center gap-3">
                    <!-- Language Switcher -->
                    <x-language-switcher />

                    <!-- Theme Toggle -->
                    <button onclick="toggleTheme()" id="theme-toggle" type="button" aria-label="Toggle Theme"
                        class="p-2 rounded-lg border border-gray-200 dark:border-gray-800 hover:border-black dark:hover:border-white transition">
                        <div class="dark:bg-white bg-black duration-200 rounded-full w-4 h-4"></div>
                    </button>

                    @auth
                        <a href="{{ route('dashboard') }}"
                            class="px-4 py-2 text-xs sm:text-sm font-semibold rounded-lg bg-black text-white dark:bg-white dark:text-black hover:opacity-90 transition">
                            {{ __('nav_dashboard') }}
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                            class="hidden sm:inline-block px-4 py-2 text-xs sm:text-sm font-medium rounded-lg hover:text-accent dark:hover:text-accent transition">
                            {{ __('nav_login') }}
                        </a>
                        <a href="{{ route('register') }}"
                            class="px-4 py-2 text-xs sm:text-sm font-semibold rounded-lg bg-black text-white dark:bg-white dark:text-black hover:opacity-90 transition shadow-sm">
                            {{ __('nav_register') }}
                        </a>
                    @endauth
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-1">

            <!-- Hero Section -->
            <section class="relative overflow-hidden py-12 lg:py-20 border-b border-gray-100 dark:border-gray-800/60">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">

                        <!-- Left Text & Search -->
                        <div class="lg:col-span-7 space-y-6 text-center lg:text-left">
                            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-medium bg-red-50 dark:bg-red-950/40 text-accent border border-red-200 dark:border-red-900/50">
                                <span class="w-2 h-2 rounded-full bg-accent animate-ping"></span>
                                <span>{{ __('live_badge') }}</span>
                            </div>

                            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold tracking-tight text-gray-900 dark:text-white leading-[1.15]">
                                {{ __('hero_title') }} <br>
                                <span class="text-accent underline decoration-accent/30 decoration-wavy decoration-2">{{ __('hero_subtitle') }}</span>
                            </h1>

                            <p class="text-base sm:text-lg text-gray-600 dark:text-gray-300 max-w-2xl mx-auto lg:mx-0 leading-relaxed">
                                {{ __('hero_description') }}
                            </p>

                            <!-- Live Search Input -->
                            <div class="pt-2 max-w-xl mx-auto lg:mx-0">
                                <form action="{{ route('service-requests.create') }}" method="GET" class="relative flex items-center">
                                    <div class="absolute left-4 text-gray-400">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                    </div>
                                    <input type="text" name="query" placeholder="{{ __('search_placeholder') }}"
                                        class="w-full pl-12 pr-32 py-3.5 text-sm rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-[#161615] text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-accent shadow-sm">
                                    <button type="submit"
                                        class="absolute right-2 px-4 py-2 text-xs sm:text-sm font-semibold rounded-lg bg-black text-white dark:bg-white dark:text-black hover:opacity-90 transition">
                                        {{ __('find_master_btn') }}
                                    </button>
                                </form>
                            </div>

                            <!-- CTA Action Buttons -->
                            <div class="flex flex-wrap items-center justify-center lg:justify-start gap-4 pt-2">
                                <a href="{{ route('service-requests.create') }}"
                                    class="px-6 py-3 text-sm font-semibold rounded-xl bg-accent text-white hover:bg-accent/90 transition shadow-lg shadow-accent/20">
                                    🚀 {{ __('find_master_btn') }}
                                </a>
                                <a href="{{ route('provider-requests.index') }}"
                                    class="px-6 py-3 text-sm font-semibold rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-[#161615] hover:border-black dark:hover:border-white transition">
                                    💼 {{ __('become_master_btn') }}
                                </a>
                            </div>

                            <!-- Trust Badges -->
                            <div class="flex items-center justify-center lg:justify-start gap-6 pt-4 text-xs text-gray-500">
                                <div class="flex items-center gap-1.5">
                                    <span class="text-green-500">✓</span> Tekshirilgan profillar
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <span class="text-green-500">✓</span> Telegram bildirishnomalar
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <span class="text-green-500">✓</span> 0% mijoz komissiyasi
                                </div>
                            </div>
                        </div>

                        <!-- Right Visual Artwork -->
                        <div class="lg:col-span-5 relative flex items-center justify-center">
                            <div class="relative w-full max-w-md">
                                <div class="absolute -inset-4 bg-gradient-to-tr from-accent/10 to-orange-400/5 rounded-3xl blur-2xl"></div>

                                <img src="{{ asset('img/usta.png') }}" alt="UstaTop"
                                    class="relative w-full h-auto drop-shadow-2xl rounded-2xl transform hover:scale-[1.02] transition duration-300">

                                <!-- Floating Glass Badge 1: Rating -->
                                <div class="absolute -top-3 -right-3 sm:top-4 sm:-right-4 p-3 bg-white/90 dark:bg-[#161615]/90 backdrop-blur-md rounded-xl border border-gray-100 dark:border-gray-800 shadow-xl flex items-center gap-2 animate-bounce duration-1000">
                                    <span class="text-xl">⭐️</span>
                                    <div>
                                        <div class="text-xs font-bold text-gray-900 dark:text-white">4.9 / 5.0</div>
                                        <div class="text-[10px] text-gray-500">1,200+ ijobiy baho</div>
                                    </div>
                                </div>

                                <!-- Floating Glass Badge 2: Telegram Bot -->
                                <a href="https://t.me/ustatpuzbot" target="_blank"
                                    class="absolute -bottom-3 -left-3 sm:bottom-4 sm:-left-4 p-3 bg-white/90 dark:bg-[#161615]/90 backdrop-blur-md rounded-xl border border-gray-100 dark:border-gray-800 shadow-xl flex items-center gap-2 hover:scale-105 transition">
                                    <span class="text-xl">✈️</span>
                                    <div>
                                        <div class="text-xs font-bold text-blue-600 dark:text-blue-400">@ustatpuzbot</div>
                                        <div class="text-[10px] text-gray-500">24/7 jonli xabarlar</div>
                                    </div>
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
            </section>

            <!-- Live Numbers / Stats Section -->
            <section class="py-10 bg-gray-50/60 dark:bg-[#121211]/60 border-b border-gray-100 dark:border-gray-800/60">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
                        <div class="p-4">
                            <div class="text-3xl sm:text-4xl font-extrabold text-gray-900 dark:text-white tracking-tight">
                                {{ number_format($stats['completed_orders']) }}+
                            </div>
                            <div class="mt-1 text-xs sm:text-sm text-gray-500 font-medium">{{ __('stats_orders') }}</div>
                        </div>
                        <div class="p-4">
                            <div class="text-3xl sm:text-4xl font-extrabold text-accent tracking-tight">
                                {{ $stats['active_masters'] }}+
                            </div>
                            <div class="mt-1 text-xs sm:text-sm text-gray-500 font-medium">{{ __('stats_masters') }}</div>
                        </div>
                        <div class="p-4">
                            <div class="text-3xl sm:text-4xl font-extrabold text-gray-900 dark:text-white tracking-tight">
                                {{ $stats['rating'] }}
                            </div>
                            <div class="mt-1 text-xs sm:text-sm text-gray-500 font-medium">{{ __('stats_rating') }}</div>
                        </div>
                        <div class="p-4">
                            <div class="text-3xl sm:text-4xl font-extrabold text-blue-500 tracking-tight">
                                &lt; 5 min
                            </div>
                            <div class="mt-1 text-xs sm:text-sm text-gray-500 font-medium">{{ __('stats_telegram') }}</div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Popular Categories Grid -->
            <section id="services" class="py-16 lg:py-24 border-b border-gray-100 dark:border-gray-800/60">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center max-w-2xl mx-auto mb-12">
                        <h2 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight">
                            {{ __('categories_title') }}
                        </h2>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                            {{ __('categories_subtitle') }}
                        </p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @php
                            $categoryIcons = [
                                '🚿', '⚡️', '🛋', '🧺', '❄️', '🎨', '🪵', '🧹', '👨‍🏭'
                            ];
                        @endphp

                        @foreach ($categories as $index => $cat)
                            <a href="{{ route('service-requests.create') }}?category_id={{ $cat->id }}"
                                class="group p-6 rounded-2xl bg-white dark:bg-[#161615] border border-gray-100 dark:border-gray-800 hover:border-accent dark:hover:border-accent hover:shadow-lg transition duration-200 flex items-start gap-4">
                                <div class="w-12 h-12 rounded-xl bg-gray-50 dark:bg-[#1f1f1d] flex items-center justify-center text-2xl group-hover:scale-110 transition duration-200 shrink-0">
                                    {{ $categoryIcons[$index % count($categoryIcons)] }}
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-base font-semibold text-gray-900 dark:text-white group-hover:text-accent transition">
                                        {{ $cat->name }}
                                    </h3>
                                    <p class="text-xs text-gray-500 mt-1">
                                        {{ $cat->users_count > 0 ? $cat->users_count . ' ta faol usta' : 'Malakali ustalar tayyor' }}
                                    </p>
                                    <div class="mt-3 text-xs font-semibold text-accent flex items-center gap-1 opacity-0 group-hover:opacity-100 transition">
                                        {{ __('find_master_btn') }} &rarr;
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </section>

            <!-- Featured Masters Showcase -->
            <section id="masters" class="py-16 lg:py-24 bg-gray-50/40 dark:bg-[#0e0e0d] border-b border-gray-100 dark:border-gray-800/60">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex flex-col sm:flex-row items-center justify-between mb-12 gap-4">
                        <div>
                            <h2 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight">
                                {{ __('top_masters_title') }}
                            </h2>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                {{ __('top_masters_subtitle') }}
                            </p>
                        </div>
                        <a href="{{ route('service-requests.create') }}"
                            class="px-4 py-2 text-xs sm:text-sm font-semibold rounded-lg border border-gray-200 dark:border-gray-800 bg-white dark:bg-[#161615] hover:border-black dark:hover:border-white transition">
                            {{ __('Barcha ustalarni ko\'rish') }} &rarr;
                        </a>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @forelse ($masters as $master)
                            <div class="p-6 rounded-2xl bg-white dark:bg-[#161615] border border-gray-100 dark:border-gray-800 shadow-sm flex flex-col justify-between hover:shadow-md transition">
                                <div>
                                    <div class="flex items-center justify-between">
                                        <div class="w-12 h-12 rounded-full bg-black dark:bg-white text-white dark:text-black font-bold flex items-center justify-center text-lg">
                                            {{ substr($master->name, 0, 1) }}
                                        </div>
                                        <div class="flex items-center gap-1 text-xs font-semibold text-amber-500 bg-amber-50 dark:bg-amber-950/40 px-2.5 py-1 rounded-full">
                                            <span>★</span> 5.0
                                        </div>
                                    </div>

                                    <h3 class="mt-4 text-base font-semibold text-gray-900 dark:text-white flex items-center gap-1.5">
                                        <span>{{ $master->name }}</span>
                                        <span class="text-blue-500 text-xs" title="Sertifikatlangan">✓</span>
                                    </h3>

                                    <div class="mt-2 flex flex-wrap gap-1.5">
                                        @foreach ($master->categories as $cat)
                                            <span class="text-[11px] px-2 py-0.5 rounded-md bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300">
                                                {{ $cat->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="mt-6 pt-4 border-t border-gray-100 dark:border-gray-800 flex items-center justify-between">
                                    <span class="text-xs text-gray-500">📍 O'zbekiston</span>
                                    <a href="{{ route('service-requests.create') }}"
                                        class="px-3.5 py-1.5 text-xs font-semibold rounded-lg bg-black text-white dark:bg-white dark:text-black hover:opacity-90 transition">
                                        {{ __('Tanlash') }}
                                    </a>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500 col-span-3 text-center py-8">
                                {{ __('Hozircha ustalar ro\'yxati yangilanmoqda...') }}
                            </p>
                        @endforelse
                    </div>
                </div>
            </section>

            <!-- How It Works Step-by-Step -->
            <section id="how-it-works" class="py-16 lg:py-24 border-b border-gray-100 dark:border-gray-800/60">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center max-w-2xl mx-auto mb-16">
                        <h2 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight">
                            {{ __('how_it_works_title') }}
                        </h2>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                            {{ __('how_it_works_subtitle') }}
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div class="p-8 rounded-2xl bg-white dark:bg-[#161615] border border-gray-100 dark:border-gray-800 text-center relative group hover:border-black dark:hover:border-white transition">
                            <div class="w-12 h-12 mx-auto rounded-full bg-red-100 dark:bg-red-950/60 text-accent font-bold flex items-center justify-center text-lg mb-4">
                                01
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">{{ __('step_1_title') }}</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('step_1_desc') }}</p>
                        </div>

                        <div class="p-8 rounded-2xl bg-white dark:bg-[#161615] border border-gray-100 dark:border-gray-800 text-center relative group hover:border-black dark:hover:border-white transition">
                            <div class="w-12 h-12 mx-auto rounded-full bg-blue-100 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 font-bold flex items-center justify-center text-lg mb-4">
                                02
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">{{ __('step_2_title') }}</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('step_2_desc') }}</p>
                        </div>

                        <div class="p-8 rounded-2xl bg-white dark:bg-[#161615] border border-gray-100 dark:border-gray-800 text-center relative group hover:border-black dark:hover:border-white transition">
                            <div class="w-12 h-12 mx-auto rounded-full bg-green-100 dark:bg-green-950/60 text-green-600 dark:text-green-400 font-bold flex items-center justify-center text-lg mb-4">
                                03
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">{{ __('step_3_title') }}</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('step_3_desc') }}</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Telegram Bot Callout Banner -->
            <section class="py-12 bg-gradient-to-r from-blue-600 to-indigo-700 text-white">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                        <div class="space-y-2 text-center md:text-left">
                            <h3 class="text-2xl sm:text-3xl font-bold tracking-tight">
                                ✈️ Telegram Bot orqali tezroq va osonroq!
                            </h3>
                            <p class="text-sm text-blue-100 max-w-xl">
                                @ustatpuzbot orqali yangi so'rovlar holatini bilib boring, usta bilan bir zumda bog'laning.
                            </p>
                        </div>
                        <a href="https://t.me/ustatpuzbot" target="_blank"
                            class="px-6 py-3 rounded-xl bg-white text-blue-700 font-bold text-sm hover:bg-blue-50 transition shadow-lg shrink-0">
                            Botni ishga tushirish (@ustatpuzbot) &rarr;
                        </a>
                    </div>
                </div>
            </section>

            <!-- FAQ Section -->
            <section id="faq" class="py-16 lg:py-24 border-b border-gray-100 dark:border-gray-800/60" x-data="{ active: 1 }">
                <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center mb-12">
                        <h2 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight">
                            {{ __('faq_title') }}
                        </h2>
                    </div>

                    <div class="space-y-4">
                        <div class="rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-[#161615] overflow-hidden">
                            <button @click="active = (active === 1 ? 0 : 1)"
                                class="w-full p-5 text-left text-sm font-semibold text-gray-900 dark:text-white flex items-center justify-between">
                                <span>{{ __('faq_q1') }}</span>
                                <span class="text-lg text-gray-400" x-text="active === 1 ? '−' : '+'"></span>
                            </button>
                            <div x-show="active === 1" class="px-5 pb-5 text-xs sm:text-sm text-gray-600 dark:text-gray-400 leading-relaxed">
                                {{ __('faq_a1') }}
                            </div>
                        </div>

                        <div class="rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-[#161615] overflow-hidden">
                            <button @click="active = (active === 2 ? 0 : 2)"
                                class="w-full p-5 text-left text-sm font-semibold text-gray-900 dark:text-white flex items-center justify-between">
                                <span>{{ __('faq_q2') }}</span>
                                <span class="text-lg text-gray-400" x-text="active === 2 ? '−' : '+'"></span>
                            </button>
                            <div x-show="active === 2" class="px-5 pb-5 text-xs sm:text-sm text-gray-600 dark:text-gray-400 leading-relaxed" style="display: none;">
                                {{ __('faq_a2') }}
                            </div>
                        </div>

                        <div class="rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-[#161615] overflow-hidden">
                            <button @click="active = (active === 3 ? 0 : 3)"
                                class="w-full p-5 text-left text-sm font-semibold text-gray-900 dark:text-white flex items-center justify-between">
                                <span>{{ __('faq_q3') }}</span>
                                <span class="text-lg text-gray-400" x-text="active === 3 ? '−' : '+'"></span>
                            </button>
                            <div x-show="active === 3" class="px-5 pb-5 text-xs sm:text-sm text-gray-600 dark:text-gray-400 leading-relaxed" style="display: none;">
                                {{ __('faq_a3') }}
                            </div>
                        </div>
                    </div>
                </div>
            </section>

        </main>

        <!-- Footer -->
        <footer class="bg-white dark:bg-[#0a0a0a] border-t border-gray-100 dark:border-gray-800 py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-6 text-xs text-gray-500">
                <div class="flex items-center gap-2">
                    <span class="font-bold text-base text-gray-900 dark:text-white">UstaTop</span>
                    <span>&copy; {{ date('Y') }} {{ __('footer_rights') }}</span>
                </div>

                <div class="flex items-center gap-6">
                    <a href="#services" class="hover:text-accent transition">{{ __('categories_title') }}</a>
                    <a href="#how-it-works" class="hover:text-accent transition">{{ __('how_it_works_title') }}</a>
                    <a href="https://t.me/ustatpuzbot" target="_blank" class="hover:text-accent transition">@ustatpuzbot</a>
                </div>
            </div>
        </footer>

        <script>
            window.AUTHENTICATED = @json(auth()->check());
            window.USER_THEME = @json(auth()->user()->theme ?? null);
        </script>
    </body>
</html>
