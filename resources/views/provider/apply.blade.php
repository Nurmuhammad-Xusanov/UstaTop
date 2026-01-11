<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Providerga Aylanmoq') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 bg-white border-b border-gray-200">
                @if (session('success'))
                    <div class="mb-4 font-medium text-sm text-green-600">
                        {{ session('success') }}
                    </div>
                @endif


                <form method="POST" action="{{ route('provider-requests.store') }}">
                    @csrf

                    <div>
                        <x-input-label for="phone" :value="__('Telefon Raqamingiz')" />
                        <x-text-input id="phone" class="block mt-1 w-full" type="text" name="phone"
                            :value="old('phone')" required autofocus />
                        <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="city" :value="__('Shahar')" />
                        <x-text-input id="city" class="block mt-1 w-full" type="text" name="city"
                            :value="old('city')" required />
                        <x-input-error :messages="$errors->get('city')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="service_ids" :value="__('Xizmat Turlari')" />
                        <select id="service_ids" name="service_ids[]" multiple
                            class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('service_ids')" class="mt-2" />
                    </div>

                    @error('telegram')
                        <p class="text-red-500">{{ $message }}</p>
                    @enderror
                    @if (auth()->user()->telegram_id)
                        <p>✅ Telegram ulangan</p>
                    @else
                        <a href="https://t.me/ustatpuzbot?start={{ auth()->id() }}" target="_blank">
                            Telegram’ni ulash
                        </a>
                    @endif


                    <div class="flex items mt-4">
                        <x-primary-button class="ml-4">
                            {{ __('So\'rov Yuborish') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
</x-app-layout>
