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

            <form method="POST" action="{{ route('service-requests.store') }}" class="space-y-5">
                @csrf
                <div>
                    <x-input-label for="category_id" :value="__('Xizmat sohasi')" class="text-xs font-medium text-gray-700 dark:text-gray-300 mb-1" />
                    <select id="category_id" name="category_id" required
                        class="w-full text-sm rounded-lg border border-gray-200 dark:border-gray-800 bg-white dark:bg-[#161615] px-3.5 py-2.5 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent">
                        <option value="">{{ __('Sohani tanlang') }}</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('category_id')" class="mt-1" />
                </div>

                {{-- Phone --}}
                <div>
                    <x-input-label for="phone" :value="__('Bog\'lanish uchun telefon')" class="text-xs font-medium text-gray-700 dark:text-gray-300 mb-1" />
                    <input id="phone" type="text" name="phone" placeholder="+998 90 123 45 67" required
                        class="w-full text-sm rounded-lg border border-gray-200 dark:border-gray-800 bg-white dark:bg-[#161615] px-3.5 py-2.5 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent">
                    <x-input-error :messages="$errors->get('phone')" class="mt-1" />
                </div>

                {{-- City --}}
                <div>
                    <x-input-label for="city" :value="__('Shahar yoki tuman')" class="text-xs font-medium text-gray-700 dark:text-gray-300 mb-1" />
                    <input id="city" type="text" name="city" placeholder="Toshkent shahri" required
                        class="w-full text-sm rounded-lg border border-gray-200 dark:border-gray-800 bg-white dark:bg-[#161615] px-3.5 py-2.5 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent">
                    <x-input-error :messages="$errors->get('city')" class="mt-1" />
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