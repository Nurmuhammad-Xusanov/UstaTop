<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
                Kategoriya qo'shish
            </h2>
        </div>

    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <form action="{{ route('admin.categories.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="name" class="block text-gray-700 dark:text-gray-300 font-bold mb-2">Kategoriya nomi:</label>
                    <input type="text" id="name" name="name" class="w-full rounded text-black dark:text-white dark:bg-gray-800 border hover:border-accent transition duration-200 focus:outline-none focus:ring focus:border-accent required">
                </div>
                <button type="submit" class="max-sm:w-full text-black dark:text-white p-1.5 border rounded hover:border-accent hover:text-accent dark:hover:text-accent transition duration-200">Saqlash</button>
        </div>
    </div>
</x-app-layout>
