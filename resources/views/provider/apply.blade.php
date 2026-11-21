<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Usta bo\'lish uchun ariza') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-[#161615] border border-gray-100 dark:border-gray-800 rounded-xl p-6 sm:p-8 shadow-sm">
                @if (session('success'))
                    <div class="mb-4 p-3 bg-green-50 dark:bg-green-950/30 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-300 rounded-md text-xs">
                        {{ session('success') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('provider-requests.store') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label for="phone" class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">
                            {{ __('Telefon Raqamingiz') }}
                        </label>
                        <input id="phone" type="text" name="phone" value="{{ old('phone') }}" required autofocus
                            class="w-full text-xs rounded-md border border-gray-300 dark:border-gray-700 bg-transparent text-gray-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-accent focus:border-accent transition duration-200" />
                        <x-input-error :messages="$errors->get('phone')" class="mt-1" />
                    </div>

                    <div>
                        <label for="city" class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">
                            {{ __('Shahar') }}
                        </label>
                        <input id="city" type="text" name="city" value="{{ old('city') }}" required
                            class="w-full text-xs rounded-md border border-gray-300 dark:border-gray-700 bg-transparent text-gray-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-accent focus:border-accent transition duration-200" />
                        <x-input-error :messages="$errors->get('city')" class="mt-1" />
                    </div>

                    <div>
                        <label for="service_ids" class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">
                            {{ __('Xizmat Turlari') }}
                        </label>
                        <select required multiple id="service_ids" name="service_ids[]"
                            class="w-full text-xs rounded-md border border-gray-300 dark:border-gray-700 bg-transparent text-gray-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-accent focus:border-accent transition duration-200 min-h-[120px]">
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" class="py-1 dark:bg-[#161615]">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('service_ids')" class="mt-1" />
                    </div>

                    <div class="pt-2 border-t border-gray-100 dark:border-gray-800">
                        @error('telegram')
                            <p class="text-xs text-red-500 mb-2">{{ $message }}</p>
                        @enderror

                        @if (auth()->user()->telegram_id)
                            <div class="inline-flex items-center gap-1.5 text-xs text-green-600 dark:text-green-400 font-medium">
                                <span>✓</span> {{ __('Telegram ulangan') }} (ID: {{ auth()->user()->telegram_id }})
                            </div>
                        @else
                            <a href="https://t.me/{{ config('services.telegram.bot_username', 'ustatpuzbot') }}?start={{ auth()->id() }}" target="_blank"
                                class="inline-flex items-center gap-1.5 text-xs text-accent hover:underline">
                                <span>TG</span> {{ __('Telegram’ni ulash') }} &rarr;
                            </a>
                        @endif
                    </div>

                    <div class="pt-3">
                        <button type="submit"
                            class="w-full py-2.5 px-4 text-xs font-semibold rounded-md border border-accent/40 bg-accent/10 text-accent hover:bg-accent hover:text-white dark:hover:text-black hover:shadow-[0_0_20px_rgba(14,165,233,0.35)] transition duration-200 tracking-wide">
                            {{ __('So\'rov Yuborish') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
