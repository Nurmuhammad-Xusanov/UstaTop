<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-600 dark:text-gray-200">
               Kategoriyalar ro'yxati
            </h2>
            <a href="{{ route('admin.categories.create') }}"
               class="btn btn-blue text-white">
                Yangi kategoriya qo'shish
            </a>
        </div>

    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach ($categories as $category)
                    <div class="p-6 text-white dark:bg-gray-800 rounded-lg shadow">
                        <h3 class="text-lg font-semibold">{{ $category->name }}</h3>
                    </div>
                @endforeach
            </div>

        </div>
    </div>
</x-app-layout>
