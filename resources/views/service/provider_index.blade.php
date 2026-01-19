<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @foreach ($requests as $request)
                <div class="mb-6 p-6 bg-white dark:bg-gray-900 rounded-lg shadow">
                    <h3 class="text-lg font-medium dark:text-white">
                        {{ $request->user->name ?? 'Unknown' }}
                    </h3>
                    <p class="dark:text-white">City: {{ $request->city }}</p>
                    <p class="dark:text-white">Phone: {{ $request->phone }}</p>
                    <p class="dark:text-white">Status: {{ $request->status }}</p>
                    {{-- PENDING --}}
                    @if ($request->status === 'pending')
                        <form method="POST" action="{{ route('provider.service-requests.accept', $request) }}">
                            @csrf
                            <button class="btn btn-green dark:text-white">Accept</button>
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
        </div>
        <div class="absolute bottom-5 right-5 text-white">
            <a href="{{ route('service-requests.create') }}" class="text-black dark:text-white border border-transparent hover:border-accent-hover dark:hover:border-accent-hover rounded-sm transition duration-200 leading-normal p-1.5">Yangi so‘rov</a>
        </div>
    </div>
</x-app-layout>