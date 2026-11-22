<x-app-layout>
    <div class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            <!-- Profile Hero Banner -->
            <div class="relative overflow-hidden rounded-2xl bg-white dark:bg-[#111110] border border-gray-200/80 dark:border-gray-800 p-6 sm:p-8 shadow-sm">
                <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 rounded-full bg-accent/5 dark:bg-accent/10 blur-3xl pointer-events-none"></div>

                <div class="relative flex flex-col sm:flex-row items-center sm:items-start gap-6 text-center sm:text-left">
                    <!-- User Avatar or Initials -->
                    <div class="relative flex-shrink-0">
                        @if ($user->avatar_url)
                            <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-24 h-24 rounded-2xl object-cover ring-4 ring-gray-100 dark:ring-gray-800 shadow-md">
                        @else
                            <div class="w-24 h-24 rounded-2xl bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 text-white text-3xl font-black flex items-center justify-center shadow-md select-none ring-4 ring-gray-100 dark:ring-gray-800">
                                {{ $user->initials }}
                            </div>
                        @endif

                        @if ($user->telegram_id)
                            <span class="absolute -bottom-1 -right-1 w-6 h-6 rounded-full bg-emerald-500 border-2 border-white dark:border-[#111110] flex items-center justify-center text-white" title="{{ __('Telegram ulangan') }}">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 14.5l-4-4 1.41-1.41L11 13.67l6.59-6.59L19 8.5l-8 8z"/>
                                </svg>
                            </span>
                        @endif
                    </div>

                    <!-- User Meta -->
                    <div class="flex-1 space-y-2">
                        <div class="flex flex-wrap items-center justify-center sm:justify-start gap-3">
                            <h1 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">
                                {{ $user->name }}
                            </h1>

                            <!-- Role Badge -->
                            @if ($user->role === 'admin')
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-500/10 text-rose-600 dark:text-rose-400 border border-rose-500/20">
                                    {{ __('Admin') }}
                                </span>
                            @elseif ($user->role === 'provider')
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-accent/10 text-accent border border-accent/20">
                                    {{ __('Usta (Mutaxassis)') }}
                                </span>
                            @else
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 border border-indigo-500/20">
                                    {{ __('Mijoz') }}
                                </span>
                            @endif

                            <!-- Email Verification Badge -->
                            @if ($user->hasVerifiedEmail())
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    {{ __('Tasdiqlangan') }}
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/20">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                    {{ __('Tasdiqlanmagan') }}
                                </span>
                            @endif
                        </div>

                        <div class="flex flex-wrap items-center justify-center sm:justify-start gap-y-1 gap-x-4 text-xs sm:text-sm text-gray-500 dark:text-gray-400">
                            <span class="inline-flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                {{ $user->email }}
                            </span>

                            <span class="inline-flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                {{ __('A\'zo bo\'lgan sana') }}: {{ $user->created_at ? $user->created_at->format('d.m.Y') : '-' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Segmented Modern Tabs Container -->
            <div x-data="{
                tab: '{{ $errors->updatePassword->isNotEmpty() ? 'security' : ($errors->userDeletion->isNotEmpty() ? 'danger' : (session('status') === 'preferences-updated' || session('status') === 'telegram-disconnected' ? 'preferences' : 'general')) }}'
            }" class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

                <!-- Navigation Sidebar -->
                <nav class="lg:col-span-3 space-y-1.5 bg-white dark:bg-[#111110] border border-gray-200/80 dark:border-gray-800 p-2 rounded-2xl shadow-sm">
                    <!-- General Tab -->
                    <button
                        type="button"
                        @click="tab = 'general'"
                        :class="tab === 'general' ? 'bg-accent/10 text-accent font-semibold' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 font-medium'"
                        class="w-full flex items-center gap-3 px-4 py-3 text-sm rounded-xl transition duration-150 text-left cursor-pointer"
                    >
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span>{{ __('Asosiy ma\'lumotlar') }}</span>
                    </button>

                    <!-- Preferences Tab -->
                    <button
                        type="button"
                        @click="tab = 'preferences'"
                        :class="tab === 'preferences' ? 'bg-accent/10 text-accent font-semibold' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 font-medium'"
                        class="w-full flex items-center gap-3 px-4 py-3 text-sm rounded-xl transition duration-150 text-left cursor-pointer"
                    >
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span>{{ __('Sinxronlash va Sozlamalar') }}</span>
                    </button>

                    <!-- Security Tab -->
                    <button
                        type="button"
                        @click="tab = 'security'"
                        :class="tab === 'security' ? 'bg-accent/10 text-accent font-semibold' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 font-medium'"
                        class="w-full flex items-center gap-3 px-4 py-3 text-sm rounded-xl transition duration-150 text-left cursor-pointer"
                    >
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        <span>{{ __('Xavfsizlik') }}</span>
                    </button>

                    <!-- Danger Zone Tab -->
                    <button
                        type="button"
                        @click="tab = 'danger'"
                        :class="tab === 'danger' ? 'bg-rose-500/10 text-rose-600 dark:text-rose-400 font-semibold' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 font-medium'"
                        class="w-full flex items-center gap-3 px-4 py-3 text-sm rounded-xl transition duration-150 text-left cursor-pointer"
                    >
                        <svg class="w-5 h-5 flex-shrink-0 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        <span>{{ __('Hisobni o\'chirish') }}</span>
                    </button>
                </nav>

                <!-- Tab Contents -->
                <div class="lg:col-span-9 bg-white dark:bg-[#111110] border border-gray-200/80 dark:border-gray-800 p-6 sm:p-8 rounded-2xl shadow-sm">
                    <!-- General Tab Panel -->
                    <div x-show="tab === 'general'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
                        <div class="max-w-2xl">
                            @include('profile.partials.update-profile-information-form')
                        </div>
                    </div>

                    <!-- Preferences Tab Panel -->
                    <div x-show="tab === 'preferences'" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
                        <div class="max-w-2xl">
                            @include('profile.partials.update-preferences-form')
                        </div>
                    </div>

                    <!-- Security Tab Panel -->
                    <div x-show="tab === 'security'" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
                        <div class="max-w-2xl">
                            @include('profile.partials.update-password-form')
                        </div>
                    </div>

                    <!-- Danger Zone Tab Panel -->
                    <div x-show="tab === 'danger'" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
                        <div class="max-w-2xl">
                            @include('profile.partials.delete-user-form')
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
