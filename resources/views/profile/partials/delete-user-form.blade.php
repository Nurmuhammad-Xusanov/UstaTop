<section class="space-y-6">
    <header>
        <h2 class="text-lg font-semibold text-rose-600 dark:text-rose-400 flex items-center gap-2">
            <svg class="w-5 h-5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            {{ __('Hisobni o\'chirish') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Hisobingiz o\'chirilgandan so\'ng, uning barcha ma\'lumotlari va buyurtmalari butunlay yo\'q qilinadi. Qaytarib bo\'lmaydi.') }}
        </p>
    </header>

    <x-danger-button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
    >{{ __('Hisobni butunlay o\'chirish') }}</x-danger-button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 flex items-center gap-2">
                <svg class="w-5 h-5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                {{ __('Hisobingizni o\'chirishga ishonchingiz komilmi?') }}
            </h2>

            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                {{ __('Hisobingiz o\'chirilgandan so\'ng, barcha resurslaringiz butunlay o\'chiriladi. Iltimos, amalni tasdiqlash uchun parolingizni kiriting.') }}
            </p>

            <div class="mt-6">
                <x-input-label for="password" value="{{ __('Parol') }}" class="sr-only" />

                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="mt-1 block w-full sm:w-3/4"
                    placeholder="{{ __('Parolingizni kiriting') }}"
                />

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Bekor qilish') }}
                </x-secondary-button>

                <x-danger-button>
                    {{ __('Hisobni o\'chirish') }}
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>
