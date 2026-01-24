<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">
            Provider Requests
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto">
        <div class="bg-white dark:bg-black rounded shadow p-6">


            @foreach($requests as $r)
                <div class="border-b border-gray-800 flex items-center justify-between py-4">
                    <p class="text-black dark:text-white">{{ $r->user->name ?? "Unknown" }}</>
                    <p class="text-black dark:text-white max-sm:hidden">{{ $r->city }}</p>
                    <p class="text-black dark:text-white">{{ $r->status }}</p>

                    <a href="{{ route('admin.provider-requests.show', $r) }}" class="text-blue-500">
                        Batafsil
                    </a>

                </div>
            @endforeach


        </div>
    </div>
</x-app-layout>