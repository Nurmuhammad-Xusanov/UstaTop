<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Sinxronlash va Sozlamalar') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Qurilmalararo mavzu va Telegram bot bilan tilni sinxronlash sozlamalari.') }}
        </p>
    </header>

    <form method="post" action="{{ route('profile.preferences') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <!-- Theme Selection -->
        <div>
            <x-input-label for="theme" :value="__('Mavzu (Rejim)')" />
            <select id="theme" name="theme"
                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-accent focus:ring-accent text-sm">
                <option value="system" {{ old('theme', $user->theme) === 'system' ? 'selected' : '' }}>{{ __('Tizim sozlamasi bo\'yicha (Avtomatik)') }}</option>
                <option value="light" {{ old('theme', $user->theme) === 'light' ? 'selected' : '' }}>{{ __('Yorug\' rejim (Light)') }}</option>
                <option value="dark" {{ old('theme', $user->theme) === 'dark' ? 'selected' : '' }}>{{ __('Qorong\'i rejim (Dark)') }}</option>
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
                    <span class="text-sm font-medium text-gray-900 dark:text-white">
                        🔄 {{ __('Mavzuni barcha qurilmalarda avtomatik sinxronlash') }}
                    </span>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                        {{ __('Kompyuterda qora rejim yoqsangiz, telefon yoki planshetingizda ham avtomatik qora rejim ochiladi (va aksincha).') }}
                    </p>
                </div>
            </label>
        </div>

        <!-- Telegram Language Sync Checkbox -->
        <div class="p-4 rounded-xl border border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-[#161615]">
            <label class="flex items-start gap-3 cursor-pointer">
                <input type="checkbox" name="sync_telegram_lang" value="1"
                    {{ old('sync_telegram_lang', $user->sync_telegram_lang ?? true) ? 'checked' : '' }}
                    class="mt-1 rounded border-gray-300 dark:border-gray-700 text-accent focus:ring-accent">
                <div>
                    <span class="text-sm font-medium text-gray-900 dark:text-white">
                        🔄 {{ __('Sayt tilini Telegram bot bilan avtomatik sinxronlash') }}
                    </span>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                        {{ __('Saytda tilni (O\'zbek, Rus, Ingliz) o\'zgartirganingizda, Telegram bot (@ustatpuzbot) ham sizga darhol o\'sha tilda xabar yuborishni boshlaydi.') }}
                    </p>
                </div>
            </label>
        </div>

        <!-- Independent Telegram Bot Language (Fallback) -->
        <div>
            <x-input-label for="telegram_locale" :value="__('Telegram Bot uchun alohida til (agar sinxronlash o\'chirilgan bo\'lsa)')" />
            <select id="telegram_locale" name="telegram_locale"
                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-accent focus:ring-accent text-sm">
                <option value="uz" {{ old('telegram_locale', $user->telegram_locale ?? 'uz') === 'uz' ? 'selected' : '' }}>O'zbekcha (UZ)</option>
                <option value="ru" {{ old('telegram_locale', $user->telegram_locale) === 'ru' ? 'selected' : '' }}>Русский (RU)</option>
                <option value="en" {{ old('telegram_locale', $user->telegram_locale) === 'en' ? 'selected' : '' }}>English (EN)</option>
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('telegram_locale')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Saqlash') }}</x-primary-button>

            @if (session('status') === 'preferences-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2500)"
                    class="text-sm text-green-600 dark:text-green-400 font-medium"
                >{{ __('Sozlamalar saqlandi.') }}</p>
            @endif
        </div>
    </form>
</section>
