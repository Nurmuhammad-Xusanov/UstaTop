<nav x-data="{ open: false }" class="bg-[#FDFDFC] dark:bg-[#0a0a0a] border-b border-gray-100 dark:border-gray-700">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}" class="dark:text-white font-semibold text-xl tracking-tight">
                        Usta<span class="text-accent">Top</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Nazorat paneli') }}
                    </x-nav-link>
                    <x-nav-link :href="route('service-requests.index')"
                        :active="request()->routeIs('service-requests.*')">
                        {{ __('Xizmatlar') }}
                    </x-nav-link>
                    <x-nav-link :href="route('provider-requests.index')"
                        :active="request()->routeIs('provider-requests.*')">
                        {{ __('Usta bo\'lish') }}
                    </x-nav-link>
                    @if (auth()->user()->role === 'admin')
                        <x-nav-link :href="route('admin.dashboard')"
                            :active="request()->routeIs('admin.*')">
                            {{ __('Admin Panel') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6 gap-3">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center gap-2 px-2.5 py-1.5 rounded-lg border border-transparent hover:border-gray-200 dark:hover:border-gray-800 text-xs font-medium text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white focus:outline-none transition ease-in-out duration-150">
                            @if (Auth::user()->avatar_url)
                                <img src="{{ Auth::user()->avatar_url }}" alt="{{ Auth::user()->name }}" class="w-6 h-6 rounded-full object-cover border border-gray-200 dark:border-gray-700" />
                            @else
                                <div class="w-6 h-6 rounded-full bg-accent/15 text-accent font-bold flex items-center justify-center text-[10px] border border-accent/30 select-none">
                                    {{ Auth::user()->initials }}
                                </div>
                            @endif

                            <div class="max-w-[120px] truncate">{{ Auth::user()->name }}</div>

                            <svg class="h-3.5 w-3.5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')" class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span>{{ __('Profile') }}</span>
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault();
                                                this.closest('form').submit();" class="flex items-center gap-2 text-rose-600 dark:text-rose-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                <span>{{ __('Log Out') }}</span>
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>

                {{-- theme-toggle --}}
                <button onclick="toggleTheme()" id="theme-toggle" type="button" class="p-1">
                    <div class="dark:bg-white bg-black duration-200 rounded-full w-5 h-5"></div>
                </button>
            </div>

            <div class="flex items-center gap-2 sm:hidden">
                {{-- theme-toggle --}}
                <button onclick="toggleTheme()" id="theme-toggle" type="button" class="p-1">
                    <div class="dark:bg-white bg-black duration-200 rounded-full w-5 h-5"></div>
                </button>
                <!-- Hamburger -->
                <div class="-me-2 flex items-center sm:hidden">
                    <button @click="open = ! open"
                        class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-accent-hover dark:hover:text-accent-hover hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-accent-hover dark:focus:text-accent-hover transition duration-150 ease-in-out">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex"
                                stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden"
                                stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Nazorat paneli') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('service-requests.index')"
                :active="request()->routeIs('service-requests.*')">
                {{ __('Xizmatlar') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('provider-requests.index')"
                :active="request()->routeIs('provider-requests.index')">
                {{ __('Usta bo\'lish') }}
            </x-responsive-nav-link>
            @if (auth()->user()->role === 'admin')
                <x-responsive-nav-link :href="route('admin.dashboard')"
                    :active="request()->routeIs('admin.*')">
                    {{ __('Admin Panel') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-2 border-t border-gray-200 dark:border-gray-800">
            <div class="px-4 flex items-center gap-3">
                @if (Auth::user()->avatar_url)
                    <img src="{{ Auth::user()->avatar_url }}" alt="{{ Auth::user()->name }}" class="w-10 h-10 rounded-full object-cover border border-gray-200 dark:border-gray-700" />
                @else
                    <div class="w-10 h-10 rounded-full bg-accent/15 text-accent font-bold flex items-center justify-center text-sm border border-accent/30 select-none">
                        {{ Auth::user()->initials }}
                    </div>
                @endif
                <div>
                    <div class="font-medium text-sm text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                    <div class="font-normal text-xs text-gray-500">{{ Auth::user()->email }}</div>
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')" class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <span>{{ __('Profile') }}</span>
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault();
                                        this.closest('form').submit();" class="flex items-center gap-2 text-rose-600 dark:text-rose-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span>{{ __('Log Out') }}</span>
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>

<script>
    window.AUTHENTICATED = @json(auth()->check());
    window.USER_THEME = @json(auth()->user()->theme ?? null);
</script>