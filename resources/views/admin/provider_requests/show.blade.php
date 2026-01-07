<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">
            Provider Requests
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto">
        <div class="bg-white dark:bg-gray-800 rounded shadow p-6">
            <h1>{{ $providerRequest->user->name ?? "Unknown" }}</h1>
            <p>Role: {{ $providerRequest->user->role ?? "Not assigned" }}</p>
            <p>City: {{ $providerRequest->city }}</p>
            <p>Phone: {{ $providerRequest->phone }}</p>
            <p>Email: {{ $providerRequest->user->email ?? "Not provided" }}</p>
            <p>Status: {{ $providerRequest->status }}</p>
            <p>Telegram id: {{ $providerRequest->user->telegram_id ?? "Not provided" }}</p>
            <p>Services:</p>
            <ul>

                {{  $providerRequest }}
                {{-- @foreach($providerRequest->services() as $service)
                    <li>{{ $service->name }}</li>
                @endforeach --}}
            </ul>
        </div>
    </div>
</x-app-layout>
