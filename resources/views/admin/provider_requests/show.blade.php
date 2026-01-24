<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">
            Provider Requests
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto">
        <div class="bg-white dark:bg-black rounded shadow p-6">
            <h1 class="text-black dark:text-white">{{ $providerRequest->user->name ?? 'Unknown' }}</h1>
            <p class="text-black dark:text-white">Role: {{ $providerRequest->user->role ?? 'Not assigned' }}</p>
            <p class="text-black dark:text-white">City: {{ $providerRequest->city }}</p>
            <p class="text-black dark:text-white">Phone: {{ $providerRequest->phone }}</p>
            <p class="text-black dark:text-white">Email: {{ $providerRequest->user->email ?? 'Not provided' }}</p>
            <p class="text-black dark:text-white">Status: {{ $providerRequest->status }}</p>
            <p class="text-black dark:text-white">Telegram id:
                {{ $providerRequest->user->telegram_id ?? 'Not provided' }}</p>
            <ul>
                @foreach ($categories as $category)
                    <li class="text-black dark:text-white">{{ $category->name }}</li>
                @endforeach
            </ul>
            <div class="flex gap-2 items-center mt-4">
                <form action="{{ route('admin.provider-requests.update', $providerRequest) }}" method="POST">
                    @csrf
                    @method('PATCH')

                    <input type="hidden" name="status" value="approved">
                    <button type="submit"
                        class="border rounded border-gray-800 hover:border-success hover:text-success dark:hover:text-success text-black dark:text-white p-1.5 transition duration-200">Approve</button>
                </form>

                <form method="POST" action="{{ route('admin.provider-requests.update', $providerRequest) }}">
                    @csrf
                    @method('PATCH')

                    <input type="hidden" name="status" value="rejected">

                    <button type="submit" class="border rounded border-gray-800 hover:border-warning hover:text-warning dark:hover:text-warning text-black dark:text-white p-1.5 transition duration-200">
                        Reject
                    </button>
                </form>

                <form method="POST" action="{{ route('admin.provider-requests.destroy', $providerRequest) }}"
                    onsubmit="return confirm('Ishonching komilmi?')">
                    @csrf
                    @method('DELETE')

                    <button type="submit" class="border rounded border-gray-800 hover:border-error hover:text-error dark:hover:text-error text-black dark:text-white p-1.5 transition duration-200">
                        Delete
                    </button>
                </form>
            </div>


        </div>
    </div>
</x-app-layout>