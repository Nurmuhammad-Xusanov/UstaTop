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
                        {{ __(session('success')) }}
                    </div>
                @endif

                @if ($errors->has('telegram'))
                    <div class="mb-4 p-3 bg-red-50 dark:bg-red-950/30 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 rounded-md text-xs">
                        {{ __($errors->first('telegram')) }}
                    </div>
                @endif
                @if ($errors->has('role'))
                    <div class="mb-4 p-3 bg-red-50 dark:bg-red-950/30 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 rounded-md text-xs">
                        {{ __($errors->first('role')) }}
                    </div>
                @endif
                @if ($errors->has('request'))
                    <div class="mb-4 p-3 bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-800 text-amber-700 dark:text-amber-300 rounded-md text-xs">
                        {{ __($errors->first('request')) }}
                    </div>
                @endif

                <form method="POST" action="{{ route('provider-requests.store') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label for="phone" class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">
                            {{ __('Telefon Raqamingiz') }}
                        </label>
                        <input id="phone" type="text" name="phone" value="{{ old('phone') }}" required autofocus
                            placeholder="+998 90 123 45 67"
                            class="w-full text-xs rounded-md border border-gray-300 dark:border-gray-700 bg-white dark:bg-[#1a1a19] text-gray-900 dark:text-white px-3 py-2 focus:outline-none focus:ring-1 focus:ring-accent focus:border-accent transition duration-200" />
                        <x-input-error :messages="$errors->get('phone')" class="mt-1" />
                    </div>

                    <div>
                        <label for="city" class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">
                            {{ __('Shahar') }}
                        </label>
                        <input id="city" type="text" name="city" value="{{ old('city') }}" required
                            placeholder="{{ __('Toshkent shahri') }}"
                            class="w-full text-xs rounded-md border border-gray-300 dark:border-gray-700 bg-white dark:bg-[#1a1a19] text-gray-900 dark:text-white px-3 py-2 focus:outline-none focus:ring-1 focus:ring-accent focus:border-accent transition duration-200" />
                        <x-input-error :messages="$errors->get('city')" class="mt-1" />
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                            {{ __('Xizmat Turlari') }}
                        </label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 max-h-56 overflow-y-auto p-2.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-gray-50/70 dark:bg-[#111110]">
                            @foreach ($categories as $category)
                                <label class="flex items-center gap-2.5 p-2 rounded-md cursor-pointer transition border border-gray-200/60 dark:border-gray-800 bg-white dark:bg-[#181817] hover:border-accent/40 has-[:checked]:border-accent has-[:checked]:bg-accent/10">
                                    <input type="checkbox" name="service_ids[]" value="{{ $category->id }}"
                                        class="rounded border-gray-300 dark:border-gray-600 text-accent focus:ring-accent bg-white dark:bg-[#20201f]"
                                        {{ in_array($category->id, old('service_ids', [])) ? 'checked' : '' }}>
                                    <span class="text-xs font-medium text-gray-900 dark:text-gray-100 select-none">
                                        {{ $category->name }}
                                    </span>
                                </label>
                            @endforeach
                        </div>
                        <x-input-error :messages="$errors->get('service_ids')" class="mt-1" />
                    </div>

                    <div class="pt-2 border-t border-gray-100 dark:border-gray-800">
                        @if (auth()->user()->telegram_id)
                            <div class="inline-flex items-center gap-1.5 text-xs text-green-600 dark:text-green-400 font-medium">
                                <span>✓</span> {{ __('Telegram ulangan') }} (ID: {{ auth()->user()->telegram_id }})
                            </div>
                        @else
                            <a href="https://t.me/{{ config('services.telegram.bot_username', 'ustatpuzbot') }}?start={{ auth()->id() }}" target="_blank"
                                class="inline-flex items-center gap-1.5 text-xs text-accent hover:underline">
                                <span class="px-1 py-0.5 rounded bg-sky-500/10 text-sky-500 font-bold text-[10px]">TG</span> {{ __('Telegram’ni ulash') }} &rarr;
                            </a>
                        @endif
                    </div>

                    <div class="pt-3">
                        <button type="submit"
                            class="w-full py-2.5 px-4 text-xs font-semibold rounded-md border border-accent/40 bg-accent/10 text-accent hover:bg-accent hover:text-white dark:hover:text-black hover:shadow-[0_0_20px_rgba(14,165,233,0.35)] transition duration-200 tracking-wide cursor-pointer">
                            {{ __('So\'rov Yuborish') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
