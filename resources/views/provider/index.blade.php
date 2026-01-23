<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @foreach ($requests as $request)
                <div>
                    <h3 class="text-lg font-medium text-gray-600">
                        {{ $request->user->name ?? 'Unknown' }}
                    </h3>
                    <p class="text-gray-600">City: {{ $request->city }}</p>
                    <p class="text-gray-600">Phone: {{ $request->phone }}</p>
                    <p class="text-gray-600">Status: {{ $request->status }}</p>
                    <form method="POST" action="{{ route('admin.provider-requests.destroy', $request) }}"
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
