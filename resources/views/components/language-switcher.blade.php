@php
    $currentLocale = app()->getLocale();
    $languages = [
        'uz' => ["name" => "O'zbek", "flag" => "🇺🇿"],
        'ru' => ["name" => "Русский", "flag" => "🇷🇺"],
        'en' => ["name" => "English", "flag" => "🇬🇧"],
    ];
@endphp

<div x-data="{ open: false }" class="relative inline-block text-left">
    <button @click="open = !open" @click.outside="open = false" type="button"
        class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium rounded-md border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] hover:border-black dark:hover:border-white transition duration-150">
        <span>{{ $languages[$currentLocale]['flag'] ?? '🌐' }}</span>
        <span class="uppercase font-semibold tracking-wider">{{ $currentLocale }}</span>
        <svg class="w-3 h-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    <div x-show="open" x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="transform opacity-0 scale-95"
        x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95"
        class="absolute right-0 z-50 mt-1.5 w-32 origin-top-right rounded-md bg-white dark:bg-[#161615] border border-[#e3e3e0] dark:border-[#3E3E3A] shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none py-1"
        style="display: none;">
        @foreach ($languages as $code => $data)
            <a href="{{ route('locale.switch', $code) }}"
                class="flex items-center gap-2 px-3 py-1.5 text-xs text-left transition {{ $currentLocale === $code ? 'font-semibold text-accent dark:text-accent bg-gray-50 dark:bg-[#1f1f1d]' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-[#252523]' }}">
                <span>{{ $data['flag'] }}</span>
                <span>{{ $data['name'] }}</span>
            </a>
        @endforeach
    </div>
</div>
