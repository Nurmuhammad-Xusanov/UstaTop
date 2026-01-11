<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">
            Provider Requests
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto">
        <div class="bg-white dark:bg-gray-800 rounded shadow p-6">
            <h1>{{ $providerRequest->user->name ?? 'Unknown' }}</h1>
            <p>Role: {{ $providerRequest->user->role ?? 'Not assigned' }}</p>
            <p>City: {{ $providerRequest->city }}</p>
            <p>Phone: {{ $providerRequest->phone }}</p>
            <p>Email: {{ $providerRequest->user->email ?? 'Not provided' }}</p>
            <p>Status: {{ $providerRequest->status }}</p>
            <p>Telegram id: {{ $providerRequest->user->telegram_id ?? 'Not provided' }}</p>
            <ul>
                @foreach ($categories as $category)
                    <li>{{ $category->name }}</li>
                @endforeach
            </ul>

            <form action="{{ route('provider-requests.update', $providerRequest) }}" method="POST" class="mt-4">
                @csrf
                @method('PATCH')

                <input type="hidden" name="status" value="approved">
                <button type="submit" class="mt-4 bg-green-500 text-white px-4 py-2 rounded">Approve</button>
            </form>

            <form method="POST" action="{{ route('provider-requests.update', $providerRequest) }}">
                @csrf
                @method('PATCH')

                <input type="hidden" name="status" value="rejected">

                <button type="submit" class="btn btn-warning">
                    Reject
                </button>
            </form>

            <form method="POST" action="{{ route('provider-requests.destroy', $providerRequest) }}"
                onsubmit="return confirm('Ishonching komilmi?')">
                @csrf
                @method('DELETE')

                <button type="submit" class="btn btn-danger">
                    Delete
                </button>
            </form>



        </div>
    </div>
</x-app-layout>
