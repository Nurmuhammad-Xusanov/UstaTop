<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white tracking-tight">
                {{ __('Yangi xizmat buyurtmasi') }}
            </h2>
            <a href="{{ route('service-requests.index') }}" class="text-xs text-accent hover:underline">
                &larr; {{ __('Xizmatlar ro\'yxatiga qaytish') }}
            </a>
        </div>
    </x-slot>

    <div class="py-8 max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-[#111110] rounded-2xl border border-gray-100 dark:border-gray-800 p-6 sm:p-8 shadow-sm">
            <div class="mb-6">
                <h3 class="text-base font-semibold text-gray-900 dark:text-white">
                    {{ __('Buyurtma ma\'lumotlarini kiriting') }}
                </h3>
                <p class="text-xs text-gray-500 mt-1">
                    {{ __('Siz kiritgan ma\'lumotlar bo\'yicha eng yaqin va mos ustalar ko\'rsatiladi.') }}
                </p>
            </div>

            @if ($errors->has('general'))
                <div class="mb-5 p-3 rounded-lg border border-amber-500/30 bg-amber-500/10 text-amber-700 dark:text-amber-300 text-xs">
                    {{ $errors->first('general') }}
                </div>
            @endif

            <!-- Telegram Connection Status -->
            <div class="mb-5 p-3.5 rounded-xl border {{ auth()->user()->telegram_id ? 'border-emerald-500/30 bg-emerald-500/5' : 'border-sky-500/30 bg-sky-500/5' }} flex items-center justify-between text-xs">
                @if (auth()->user()->telegram_id)
                    <div class="flex items-center gap-2 text-emerald-600 dark:text-emerald-400 font-medium">
                        <span>✓</span>
                        <span>{{ __('Telegram hisobingiz ulangan (ID: :id)', ['id' => auth()->user()->telegram_id]) }}</span>
                    </div>
                @else
                    <div class="flex items-center gap-2 text-gray-700 dark:text-gray-300">
                        <span class="w-6 h-6 rounded-md bg-sky-500/10 text-sky-500 flex items-center justify-center font-bold text-[10px]">TG</span>
                        <span>{{ __('Telegram ulanmagan (ustalardan tezkor javob olish uchun ulab qo\'yishingiz mumkin)') }}</span>
                    </div>
                    <a href="https://t.me/{{ config('services.telegram.bot_username', 'ustatpuzbot') }}?start={{ auth()->id() }}" target="_blank"
                        class="px-2.5 py-1 text-[11px] font-semibold rounded-md border border-accent/40 bg-accent/10 text-accent hover:bg-accent hover:text-white dark:hover:text-black transition">
                        {{ __('Ulash') }} &rarr;
                    </a>
                @endif
            </div>

            <form method="POST" action="{{ route('service-requests.store') }}" class="space-y-5">
                @csrf
                <div>
                    <x-input-label for="category_id" :value="__('Xizmat sohasi')" class="text-xs font-medium text-gray-700 dark:text-gray-300 mb-1" />
                    <select id="category_id" name="category_id" required
                        class="w-full text-sm rounded-lg border border-gray-200 dark:border-gray-800 bg-white dark:bg-[#161615] px-3.5 py-2.5 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent">
                        <option value="">{{ __('Sohani tanlang') }}</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('category_id')" class="mt-1" />
                </div>

                {{-- Phone --}}
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <x-input-label for="phone" :value="__('Bog\'lanish uchun telefon')" class="text-xs font-medium text-gray-700 dark:text-gray-300" />
                        @if (auth()->user()->telegram_id)
                            <span class="text-[10px] text-gray-400">({{ __('Ixtiyoriy, chunki Telegram ulangan') }})</span>
                        @else
                            <span class="text-[10px] text-amber-500 font-medium">({{ __('Majburiy yoki Telegramni ulang') }})</span>
                        @endif
                    </div>
                    <input id="phone" type="text" name="phone" value="{{ old('phone') }}" placeholder="+998 90 123 45 67" {{ !auth()->user()->telegram_id ? 'required' : '' }}
                        class="w-full text-sm rounded-lg border border-gray-200 dark:border-gray-800 bg-white dark:bg-[#161615] px-3.5 py-2.5 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent">
                    <x-input-error :messages="$errors->get('phone')" class="mt-1" />
                </div>

                {{-- City --}}
                <div>
                    <x-input-label for="city" :value="__('Shahar yoki tuman')" class="text-xs font-medium text-gray-700 dark:text-gray-300 mb-1" />
                    <input id="city" type="text" name="city" value="{{ old('city') }}" placeholder="Toshkent shahri" required
                        class="w-full text-sm rounded-lg border border-gray-200 dark:border-gray-800 bg-white dark:bg-[#161615] px-3.5 py-2.5 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent">
                    <x-input-error :messages="$errors->get('city')" class="mt-1" />
                </div>

                {{-- Comment / Ish tafsilotlari --}}
                <div>
                    <x-input-label for="comment" :value="__('Mijoz izohi / Muammo tafsilotlari')" class="text-xs font-medium text-gray-700 dark:text-gray-300 mb-1" />
                    <textarea id="comment" name="comment" rows="3" placeholder="{{ __('Qanday ish bajarilishi kerak? Qo\'shimcha ma\'lumot yoki qulay vaqtni yozing...') }}"
                        class="w-full text-sm rounded-lg border border-gray-200 dark:border-gray-800 bg-white dark:bg-[#161615] px-3.5 py-2.5 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent">{{ old('comment') }}</textarea>
                    <x-input-error :messages="$errors->get('comment')" class="mt-1" />
                </div>

                <div class="pt-2 flex items-center justify-end gap-3">
                    <a href="{{ route('service-requests.index') }}"
                        class="px-4 py-2 text-xs font-medium rounded-lg border border-gray-200 dark:border-gray-800 text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-neutral-900 transition">
                        {{ __('Bekor qilish') }}
                    </a>
                    <button type="submit"
                        class="px-5 py-2.5 text-xs font-semibold rounded-lg border border-accent/40 bg-accent/10 text-accent hover:bg-accent hover:text-white dark:hover:text-black hover:shadow-[0_0_20px_rgba(14,165,233,0.35)] transition cursor-pointer">
                        {{ __('Buyurtma yuborish') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>