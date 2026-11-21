@php
    $currentLocale = app()->getLocale();
    $languages = [
        'uz' => 'UZ',
        'ru' => 'RU',
        'en' => 'EN',
    ];
@endphp

<div x-data="{ open: false }" class="relative inline-block text-left">
    <button @click="open = !open" @click.outside="open = false" type="button"
        class="inline-flex items-center gap-1 px-2 py-1 text-xs font-semibold rounded border border-gray-200 dark:border-gray-800 bg-transparent text-gray-800 dark:text-gray-200 hover:border-black dark:hover:border-white transition">
        <span>{{ $languages[$currentLocale] ?? strtoupper($currentLocale) }}</span>
        <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    <div x-show="open" x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="transform opacity-0 scale-95"
        x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95"
        class="absolute right-0 z-50 mt-1 w-24 origin-top-right rounded border border-gray-200 dark:border-gray-800 bg-white dark:bg-[#111110] shadow-md focus:outline-none py-1"
        style="display: none;">
        @foreach ($languages as $code => $label)
            <a href="{{ route('locale.switch', $code) }}"
                class="block px-3 py-1.5 text-xs text-left transition {{ $currentLocale === $code ? 'font-bold text-black dark:text-white bg-gray-100 dark:bg-[#1a1a19]' : 'text-gray-600 dark:text-gray-400 hover:text-black dark:hover:text-white hover:bg-gray-50 dark:hover:bg-[#161615]' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>
</div>
