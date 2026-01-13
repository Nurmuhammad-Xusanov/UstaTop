<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
            Admin Dashboard
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Provider Requests --}}
                <a href="{{ route('provider-requests.index') }}"
                   class="p-6 text-gray-600 shadow hover:scale-[1.02] transition">
                    <h3 class="text-lg font-semibold">Provider Requests</h3>
                </a>

                {{-- Logout --}}
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button
                        class="w-full text-left p-6 bg-white dark:bg-gray-800 rounded-lg shadow hover:bg-red-50 dark:hover:bg-red-900 transition">
                        <h3 class="text-lg font-semibold text-red-600">Logout</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Admin paneldan chiqish
                        </p>
                    </button>
                </form>

            </div>

        </div>
    </div>
</x-app-layout>
