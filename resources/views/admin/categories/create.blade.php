<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Kategoriya qo\'shish') }}
            </h2>
            <a href="{{ route('admin.categories.index') }}" class="text-xs text-gray-500 hover:text-black dark:hover:text-white transition">
                &larr; {{ __('Kategoriyalar ro\'yxati') }}
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-[#161615] rounded-xl border border-gray-100 dark:border-gray-800 p-6 shadow-sm">
                <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="name" class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">
                            {{ __('Kategoriya nomi') }}
                        </label>
                        <input type="text" id="name" name="name" required
                            class="w-full text-sm rounded-md border border-gray-200 dark:border-gray-800 bg-transparent px-3 py-2 text-gray-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-black dark:focus:ring-white">
                    </div>
                    <div class="flex justify-end gap-3 pt-2">
                        <a href="{{ route('admin.categories.index') }}"
                            class="px-4 py-2 text-xs font-medium rounded-md border border-gray-200 dark:border-gray-800 text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-neutral-900 transition">
                            {{ __('Bekor qilish') }}
                        </a>
                        <button type="submit"
                            class="px-4 py-2 text-xs font-semibold rounded-md border border-black bg-black text-white dark:bg-white dark:text-black dark:border-white hover:opacity-90 transition">
                            {{ __('Saqlash') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
