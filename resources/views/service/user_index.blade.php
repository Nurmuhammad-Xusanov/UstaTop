<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Siz clientsiz, sizning so‘rovlaringiz:') }}
            </h2>
            <button><a href="{{ route('service-requests.create') }}" class="btn btn-primary">Yangi so‘rov</a></button>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @foreach ($requests as $request)
                <div class="mb-6 p-6 bg-white dark:bg-gray-800 rounded-lg shadow">
                    <h3 class="text-lg font-medium text-gray-600">
                        {{ $request->user->name ?? 'Unknown' }}
                    </h3>
                    <p class="text-gray-600">City: {{ $request->city }}</p>
                    <p class="text-gray-600">Phone: {{ $request->phone }}</p>
                    <p class="text-gray-600">Status: {{ $request->status }}</p>
                    @if ($request->status === 'provider_done' && $request->user_id === auth()->id())
                        <form method="POST" action="{{ route('client.service-requests.confirm', $request) }}">
                            @csrf
                            <button class="bg-green-600 text-white px-3 py-1 rounded">
                                Ish tugadi
                            </button>
                        </form>
                    @endif

                    <form method="POST" action="{{ route('service-requests.destroy', $request) }}"
                        onsubmit="return confirm('Ishonching komilmi?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded">
                            Delete
                        </button>
                    </form>
                </div>
            @endforeach
        </div>
</x-app-layout>
