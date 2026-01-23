<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-600 dark:text-gray-200">
                Kategoriyalar ro'yxati
            </h2>
            <a href="{{ route('admin.categories.create') }}" class="btn btn-blue text-white">
                Yangi kategoriya qo'shish
            </a>
        </div>

    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @foreach ($categories as $c)
                <div class="flex justify-between items-center border-b border-gray-800">
                    <p class="text-black dark:text-white">{{ $c->name }}</p>
                    <form action="{{ route('admin.categories.destroy', $c->id) }}" method="POST"
                        onsubmit="return confirm('Ishonching komilmi?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="mt-2 p-1.5 rounded-sm  hover:text-accent dark:hover:text-accent transition duration-200 text-dark dark:text-white">
                            O'chirish
                        </button>
                    </form>
                </div>
            @endforeach
        </div>
        <a href="{{ route('admin.categories.create') }}" class="pointer fixed bottom-5 right-5 text-black dark:text-white p-1.5 border border-gray-800 rounded-sm hover:border-accent hover:text-accent dark:hover:text-accent transition duration-200" >Kategoriya qo'shish</a>
    </div>
</x-app-layout>