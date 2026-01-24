<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @foreach ($requests as $request)
                <div>
                    <h3 class="text-lg font-medium text-black dark:text-white">
                        {{ $request->user->name ?? 'Unknown' }}
                    </h3>
                    <p class="text-black dark:text-white">City: {{ $request->city }}</p>
                    <p class="text-black dark:text-white">Phone: {{ $request->phone }}</p>
                    <p class="text-black dark:text-white">Status: {{ $request->status }}</p>
                    <form method="POST" action="{{ route('provider-requests.destroy', $request) }}"
                        onsubmit="return confirm('Ishonching komilmi?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="mt-2 border rounded border-gray-800 hover:border-error hover:text-error dark:hover:text-error text-black dark:text-white p-1.5 transition duration-200">
                            Delete
                        </button>
                    </form>
                </div>
            @endforeach
        </div>
</x-app-layout>
