<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Siz provaydersiz') }}
            </h2>

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
                {{-- PENDING --}}
                @if ($request->status === 'pending')
                    <form method="POST" action="{{ route('provider.service-requests.accept', $request) }}">
                        @csrf
                        <button class="btn btn-green">Accept</button>
                    </form>
                @endif

                {{-- ACCEPTED --}}
                @if ($request->status === 'accepted' && $request->provider_id === auth()->id())
                    <form method="POST" action="{{ route('provider.service-requests.done', $request) }}">
                        @csrf
                        <button class="btn btn-blue">Ishni tugatdim</button>
                    </form>

                    <form method="POST" action="{{ route('provider.service-requests.cancel', $request) }}">
                        @csrf
                        <button class="btn btn-red">Cancel</button>
                    </form>
                @endif

                {{-- PROVIDER DONE --}}
                @if ($request->status === 'provider_done')
                    <p class="text-yellow-600">Client tasdiqlashi kutilmoqda</p>
                @endif
            @endforeach
        </div>
</x-app-layout>
