<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @foreach ($requests as $request)
                <div class="mb-6 p-6 bg-white dark:bg-[#0a0a0a]  rounded-lg shadow">
                    <h3 class="text-lg font-medium text-gray-600">
                        {{ $request->user->name ?? 'Unknown' }}
                    </h3>
                    <p class="text-gray-600">City: {{ $request->city }}</p>
                    <p class="text-gray-600">Phone: {{ $request->phone }}</p>
                    <p class="text-gray-600">Status: {{ $request->status }}</p>
                    @if ($request->status === 'provider_done' && $request->user_id === auth()->id())
                        <form method="POST" action="{{ route('client.service-requests.confirm', $request) }}">
                            @csrf
                            <button class="mt-2 border p-1.5 rounded-sm hover:border-info hover:text-info dark:hover:text-info transition duration-200 text-dark dark:text-white">
                                Ish tugadi
                            </button>
                        </form>
                    @endif

                    <form method="POST" action="{{ route('service-requests.destroy', $request) }}"
                        onsubmit="return confirm('Ishonching komilmi?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="mt-2 border p-1.5 rounded-sm hover:border-warning hover:text-warning dark:hover:text-warning transition duration-200 text-dark dark:text-white">
                            O'chirish
                        </button>
                    </form>
                </div>
            @endforeach
        </div>
        <div class="fixed bottom-5 right-5 text-white">
            <a href="{{ route('service-requests.create') }}"
                class="text-black dark:text-white border border-transparent hover:text-accent dark:hover:text-accent hover:border-accent-hover dark:hover:border-accent-hover rounded-sm transition duration-200 leading-normal p-1.5">Yangi
                so‘rov</a>
        </div>
    </div>
</x-app-layout>