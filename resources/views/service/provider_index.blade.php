<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Mavjud buyurtmalar') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-4">
            @forelse ($requests as $request)
                <div class="p-6 bg-white dark:bg-[#161615] border border-gray-100 dark:border-gray-800 rounded-xl shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-3">
                            <h3 class="text-base font-semibold text-gray-900 dark:text-white">
                                {{ $request->category->name ?? __('Xizmat') }}
                            </h3>
                            @php
                                $statusClasses = match($request->status) {
                                    'completed' => 'text-emerald-700 bg-emerald-50 border-emerald-200 dark:text-emerald-300 dark:bg-emerald-950/40 dark:border-emerald-800',
                                    'accepted' => 'text-sky-700 bg-sky-50 border-sky-200 dark:text-sky-300 dark:bg-sky-950/40 dark:border-sky-800',
                                    'provider_done' => 'text-indigo-700 bg-indigo-50 border-indigo-200 dark:text-indigo-300 dark:bg-indigo-950/40 dark:border-indigo-800',
                                    'pending' => 'text-amber-700 bg-amber-50 border-amber-200 dark:text-amber-300 dark:bg-amber-950/40 dark:border-amber-800',
                                    'cancelled' => 'text-rose-700 bg-rose-50 border-rose-200 dark:text-rose-300 dark:bg-rose-950/40 dark:border-rose-800',
                                    default => 'text-gray-700 bg-gray-50 border-gray-200 dark:text-gray-300 dark:bg-neutral-900 dark:border-neutral-800',
                                };
                            @endphp
                            <span class="inline-block px-2.5 py-0.5 rounded text-[10px] font-semibold uppercase tracking-wider border {{ $statusClasses }}">
                                {{ $request->status }}
                            </span>
                        </div>
                        <div class="mt-2 text-xs text-gray-500 dark:text-gray-400 space-y-1">
                            <div><span class="text-gray-400">{{ __('Mijoz') }}:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $request->user->name ?? __('Noma\'lum') }}</span></div>
                            <div><span class="text-gray-400">{{ __('Email') }}:</span> <span class="font-mono text-gray-700 dark:text-gray-300">{{ $request->email ?? ($request->user->email ?? '') }}</span></div>
                            <div><span class="text-gray-400">{{ __('Telefon') }}:</span> <span class="font-mono text-gray-700 dark:text-gray-300">{{ $request->phone }}</span></div>
                            <div><span class="text-gray-400">{{ __('Shahar') }}:</span> {{ $request->city }}</div>
                            @if ($request->comment)
                                <div><span class="text-gray-400">{{ __('Mijoz izohi') }}:</span> <span class="italic text-gray-700 dark:text-gray-300">"{{ $request->comment }}"</span></div>
                            @endif
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        {{-- PENDING --}}
                        @if ($request->status === 'pending')
                            <form method="POST" action="{{ route('provider.service-requests.accept', $request) }}">
                                @csrf
                                <button type="submit" class="px-3.5 py-1.5 text-xs font-semibold rounded-lg border border-accent/40 bg-accent/10 text-accent hover:bg-accent hover:text-white dark:hover:text-black hover:shadow-[0_0_15px_rgba(14,165,233,0.3)] transition duration-200 cursor-pointer">
                                    {{ __('Qabul qilish') }}
                                </button>
                            </form>
                        @endif

                        {{-- ACCEPTED --}}
                        @if ($request->status === 'accepted' && $request->provider_id === auth()->id())
                            <form method="POST" action="{{ route('provider.service-requests.done', $request) }}">
                                @csrf
                                <button type="submit" class="px-3.5 py-1.5 text-xs font-semibold rounded-lg border border-emerald-500/30 bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 hover:bg-emerald-500 hover:text-white transition duration-200 cursor-pointer">
                                    ✓ {{ __('Ishni tugatdim') }}
                                </button>
                            </form>

                            <form method="POST" action="{{ route('provider.service-requests.cancel', $request) }}"
                                onsubmit="return confirm('{{ __('Ishni yakunlay olmadingizmi? Buyurtma boshqa ustalar uchun qayta ochiladi.') }}')">
                                @csrf
                                <button type="submit" class="px-3.5 py-1.5 text-xs font-medium rounded-lg border border-rose-500/30 bg-rose-500/10 text-rose-600 dark:text-rose-400 hover:bg-rose-500 hover:text-white transition duration-200 cursor-pointer">
                                    ✕ {{ __('Ish yakunlanmadi') }}
                                </button>
                            </form>
                        @endif

                        {{-- PROVIDER DONE --}}
                        @if ($request->status === 'provider_done')
                            <span class="text-xs text-amber-600 dark:text-amber-400 font-medium">
                                {{ __('Mijoz tasdiqlashi kutilmoqda') }}
                            </span>
                        @endif
                    </div>
                </div>
            @empty
                <div class="p-10 text-center bg-white dark:bg-[#161615] border border-gray-100 dark:border-gray-800 rounded-xl">
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Hech qanday buyurtma topilmadi.') }}</p>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>