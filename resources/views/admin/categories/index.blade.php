<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Kategoriyalar ro\'yxati') }}
            </h2>
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.dashboard') }}" class="text-xs text-gray-500 hover:text-black dark:hover:text-white transition">
                    &larr; {{ __('Dashboardga qaytish') }}
                </a>
                <a href="{{ route('admin.categories.create') }}" class="inline-flex items-center px-3.5 py-1.5 text-xs font-semibold rounded-lg border border-accent/40 bg-accent/10 text-accent hover:bg-accent hover:text-white dark:hover:text-black hover:shadow-[0_0_15px_rgba(14,165,233,0.35)] transition">
                    {{ __('Yangi kategoriya qo\'shish') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-4">
            <div class="bg-white dark:bg-[#161615] rounded-xl border border-gray-100 dark:border-gray-800 shadow-sm divide-y divide-gray-100 dark:divide-gray-800">
                @forelse ($categories as $c)
                    <div class="p-4 flex justify-between items-center hover:bg-gray-50/50 dark:hover:bg-[#1a1a19]/50 transition">
                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $c->name }}</span>
                        <form action="{{ route('admin.categories.destroy', $c->id) }}" method="POST"
                            onsubmit="return confirm('{{ __('Haqiqatan ham bu kategoriyani o\'chirmoqchimisiz?') }}')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="text-xs text-red-600 hover:text-red-800 dark:hover:text-red-400 font-medium transition">
                                {{ __('O\'chirish') }}
                            </button>
                        </form>
                    </div>
                @empty
                    <div class="p-8 text-center text-sm text-gray-500">
                        {{ __('Hech qanday kategoriya topilmadi.') }}
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>