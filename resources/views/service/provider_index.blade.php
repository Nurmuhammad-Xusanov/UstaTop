<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @foreach ($requests as $request)
                <div class="mb-6 p-6 bg-white dark:bg-[#0a0a0a] rounded-lg shadow">
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
                            <button
                                class="mt-2 border p-1.5 rounded-sm hover:border-accent hover:text-accent dark:hover:text-accent transition duration-200 text-dark dark:text-white">Accept</button>
                        </form>
                    @endif

                    {{-- ACCEPTED --}}
                    @if ($request->status === 'accepted' && $request->provider_id === auth()->id())
                        <div class="">
                            <form method="POST" action="{{ route('provider.service-requests.done', $request) }}">
                                @csrf
                                <button class="mt-2 border p-1.5 rounded-sm hover:border-accent hover:text-accent dark:hover:text-accent transition duration-200 text-dark dark:text-white">Ishni tugatdim</button>
                            </form>

                            <form method="POST" action="{{ route('provider.service-requests.cancel', $request) }}">
                                @csrf
                                <button class="mt-2 border p-1.5 rounded-sm hover:border-warning hover:text-warning dark:hover:text-warning transition duration-200 text-dark dark:text-white">Bekor qilish</button>
                            </form>
                        </div>
                    @endif

                    {{-- PROVIDER DONE --}}
                    @if ($request->status === 'provider_done')
                        <p class="text-yellow-600">Client tasdiqlashi kutilmoqda</p>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
    <div class="fixed bottom-5 right-5 text-white">
        <a href="{{ route('service-requests.create') }}"
            class="text-black dark:text-white border border-transparent hover:border-accent-hover dark:hover:border-accent-hover rounded-sm transition duration-200 leading-normal p-1.5">Yangi
            so‘rov</a>
    </div>
    </div>
</x-app-layout>