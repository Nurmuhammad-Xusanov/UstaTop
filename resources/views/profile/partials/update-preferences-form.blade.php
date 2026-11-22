<section>
    <header>
        <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 flex items-center gap-2">
            <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            {{ __('Sinxronlash va Sozlamalar') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Sayt tili, mavzu va Telegram bot bilan sinxronlash sozlamalari.') }}
        </p>
    </header>

    <form method="post" action="{{ route('profile.preferences') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <!-- Website Language Selection -->
        <div>
            <div class="flex items-center gap-2 mb-1.5">
                <svg class="w-4 h-4 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                </svg>
                <x-input-label for="locale" :value="__('Sayt tili')" class="!mb-0 font-medium" />
            </div>
            <select id="locale" name="locale"
                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-300 focus:border-accent focus:ring-accent text-sm">
                <option value="uz" class="bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100" {{ old('locale', $user->locale ?? app()->getLocale()) === 'uz' ? 'selected' : '' }}>O'zbekcha (UZ)</option>
                <option value="ru" class="bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100" {{ old('locale', $user->locale ?? app()->getLocale()) === 'ru' ? 'selected' : '' }}>Русский (RU)</option>
                <option value="en" class="bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100" {{ old('locale', $user->locale ?? app()->getLocale()) === 'en' ? 'selected' : '' }}>English (EN)</option>
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('locale')" />
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                {{ __('Sayt interfeysi qaysi tilda ko\'rinishini belgilaydi.') }}
            </p>
        </div>

        <!-- Theme Selection -->
        <div>
            <div class="flex items-center gap-2 mb-1.5">
                <svg class="w-4 h-4 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                </svg>
                <x-input-label for="theme" :value="__('Mavzu (Rejim)')" class="!mb-0 font-medium" />
            </div>
            <select id="theme" name="theme"
                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-300 focus:border-accent focus:ring-accent text-sm">
                <option value="system" class="bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100" {{ old('theme', $user->theme) === 'system' ? 'selected' : '' }}>{{ __('Tizim sozlamasi bo\'yicha (Avtomatik)') }}</option>
                <option value="light" class="bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100" {{ old('theme', $user->theme) === 'light' ? 'selected' : '' }}>{{ __('Yorug\' rejim (Light)') }}</option>
                <option value="dark" class="bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100" {{ old('theme', $user->theme) === 'dark' ? 'selected' : '' }}>{{ __('Qorong\'i rejim (Dark)') }}</option>
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('theme')" />
        </div>

        <!-- Cross-device Theme Sync Checkbox -->
        <div class="p-4 rounded-xl border border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-[#161615]">
            <label class="flex items-start gap-3 cursor-pointer">
                <input type="checkbox" name="sync_theme" value="1"
                    {{ old('sync_theme', $user->sync_theme ?? true) ? 'checked' : '' }}
                    class="mt-1 rounded border-gray-300 dark:border-gray-700 text-accent focus:ring-accent">
                <div>
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">
                            {{ __('Mavzuni barcha qurilmalarda avtomatik sinxronlash') }}
                        </span>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                        {{ __('Kompyuterda qora rejim yoqsangiz, telefon yoki planshetingizda ham avtomatik qora rejim ochiladi (va aksincha).') }}
                    </p>
                </div>
            </label>
        </div>

        <!-- Telegram Connection Status & Disconnect -->
        <div class="p-4 rounded-xl border border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-[#161615]">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">
                            {{ __('Telegram hisobi') }}
                        </span>
                    </div>
                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1 flex items-center gap-2">
                        @if ($user->telegram_id)
                            <span class="inline-flex items-center gap-1.5 text-emerald-600 dark:text-emerald-400 font-medium">
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                {{ __('Ulangan') }} (ID: {{ $user->telegram_id }})
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 text-gray-500 dark:text-gray-400">
                                <span class="w-2 h-2 rounded-full bg-gray-400"></span>
                                {{ __('Ulanmagan') }}
                            </span>
                        @endif
                    </div>
                </div>

                <div>
                    @if ($user->telegram_id)
                        <button type="button"
                            onclick="if(confirm('{{ __('Telegram hisobini uzishga ishonchingiz komilmi?') }}')) document.getElementById('disconnect-telegram-form').submit();"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-lg border border-rose-500/30 bg-rose-500/10 text-rose-600 dark:text-rose-400 hover:bg-rose-500 hover:text-white transition duration-200 cursor-pointer">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            {{ __('Telegram hisobini uzish') }}
                        </button>
                    @else
                        <a href="https://t.me/{{ config('services.telegram.bot_username', 'ustatpuzbot') }}?start={{ $user->id }}"
                            target="_blank"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-lg border border-accent/40 bg-accent/10 text-accent hover:bg-accent hover:text-white dark:hover:text-black transition duration-200">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                            {{ __('Telegramni ulash') }}
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Telegram Language Sync Checkbox -->
        <div class="p-4 rounded-xl border border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-[#161615]">
            <label class="flex items-start gap-3 cursor-pointer">
                <input type="checkbox" name="sync_telegram_lang" value="1"
                    {{ old('sync_telegram_lang', $user->sync_telegram_lang ?? true) ? 'checked' : '' }}
                    class="mt-1 rounded border-gray-300 dark:border-gray-700 text-accent focus:ring-accent">
                <div>
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">
                            {{ __('Sayt tilini Telegram bot bilan avtomatik sinxronlash') }}
                        </span>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                        {{ __('Saytda tilni (O\'zbek, Rus, Ingliz) o\'zgartirganingizda, Telegram bot (@ustatpuzbot) ham sizga darhol o\'sha tilda xabar yuborishni boshlaydi.') }}
                    </p>
                </div>
            </label>
        </div>

        <!-- Independent Telegram Bot Language (Fallback) -->
        <div>
            <div class="flex items-center gap-2 mb-1.5">
                <svg class="w-4 h-4 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                </svg>
                <x-input-label for="telegram_locale" :value="__('Telegram Bot uchun alohida til (agar sinxronlash o\'chirilgan bo\'lsa)')" class="!mb-0 font-medium" />
            </div>
            <select id="telegram_locale" name="telegram_locale"
                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-300 focus:border-accent focus:ring-accent text-sm">
                <option value="uz" class="bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100" {{ old('telegram_locale', $user->telegram_locale ?? 'uz') === 'uz' ? 'selected' : '' }}>O'zbekcha (UZ)</option>
                <option value="ru" class="bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100" {{ old('telegram_locale', $user->telegram_locale) === 'ru' ? 'selected' : '' }}>Русский (RU)</option>
                <option value="en" class="bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100" {{ old('telegram_locale', $user->telegram_locale) === 'en' ? 'selected' : '' }}>English (EN)</option>
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('telegram_locale')" />
        </div>

        <div class="flex items-center gap-4 pt-2">
            <x-primary-button>{{ __('Saqlash') }}</x-primary-button>

            @if (session('status') === 'preferences-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2500)"
                    class="text-sm text-emerald-600 dark:text-emerald-400 font-medium flex items-center gap-1.5"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    {{ __('Sozlamalar saqlandi.') }}
                </p>
            @endif

            @if (session('status') === 'telegram-disconnected')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2500)"
                    class="text-sm text-rose-600 dark:text-rose-400 font-medium flex items-center gap-1.5"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    {{ __('Telegram hisobi muvaffaqiyatli uzildi.') }}
                </p>
            @endif
        </div>
    </form>

    <form id="disconnect-telegram-form" method="post" action="{{ route('profile.telegram.disconnect') }}" class="hidden">
        @csrf
        @method('delete')
    </form>
</section>
