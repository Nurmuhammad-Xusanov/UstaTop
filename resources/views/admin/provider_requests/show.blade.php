<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white tracking-tight">
                {{ __('Arizani ko\'rib chiqish') }} #{{ $providerRequest->id }}
            </h2>
            <a href="{{ route('admin.provider-requests.index') }}" class="text-xs text-gray-500 hover:text-black dark:hover:text-white transition">
                &larr; {{ __('Ortga qaytish') }}
            </a>
        </div>
    </x-slot>

    <div class="py-6 max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">
        @if (session('success'))
            <div class="p-3 bg-gray-50 dark:bg-[#161615] border border-gray-200 dark:border-gray-800 text-gray-800 dark:text-gray-200 rounded text-xs">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white dark:bg-[#111110] rounded-lg border border-gray-200 dark:border-gray-800 p-6 shadow-sm space-y-6">
            <div class="flex items-center justify-between pb-4 border-b border-gray-100 dark:border-gray-800">
                <div>
                    <h3 class="text-base font-semibold text-gray-900 dark:text-white">
                        {{ $providerRequest->user->name ?? 'Unknown' }}
                    </h3>
                    <p class="text-xs text-gray-400 mt-0.5">
                        {{ $providerRequest->user->email ?? 'No email' }}
                    </p>
                </div>
                @php
                    $reqBadgeClass = match($providerRequest->status) {
                        'approved' => 'bg-emerald-500/10 border-emerald-500/30 text-emerald-600 dark:text-emerald-400',
                        'rejected' => 'bg-rose-500/10 border-rose-500/30 text-rose-600 dark:text-rose-400',
                        default => 'bg-amber-500/10 border-amber-500/30 text-amber-600 dark:text-amber-400',
                    };
                @endphp
                <span class="px-3 py-1 text-xs font-semibold uppercase tracking-wider rounded-lg border {{ $reqBadgeClass }}">
                    {{ __('status_' . $providerRequest->status) }}
                </span>
            </div>

            <div class="grid grid-cols-2 gap-4 text-xs">
                <div>
                    <span class="text-gray-400 uppercase tracking-wider text-[10px]">{{ __('city_col') }}</span>
                    <div class="text-gray-900 dark:text-white font-medium mt-1">{{ $providerRequest->city }}</div>
                </div>
                <div>
                    <span class="text-gray-400 uppercase tracking-wider text-[10px]">{{ __('phone_col') }}</span>
                    <div class="text-gray-900 dark:text-white font-mono mt-1">{{ $providerRequest->phone }}</div>
                </div>
                <div>
                    <span class="text-gray-400 uppercase tracking-wider text-[10px]">{{ __('telegram_col') }}</span>
                    <div class="text-gray-900 dark:text-white font-mono mt-1">{{ $providerRequest->user->telegram_id ?? __('Ulanmagan') }}</div>
                </div>
                <div>
                    <span class="text-gray-400 uppercase tracking-wider text-[10px]">{{ __('created_at_col') }}</span>
                    <div class="text-gray-900 dark:text-white mt-1">{{ $providerRequest->created_at->format('d.m.Y H:i') }}</div>
                </div>
            </div>

            <div>
                <span class="text-gray-400 uppercase tracking-wider text-[10px]">{{ __('Tanlangan sohalar') }}</span>
                <div class="flex flex-wrap gap-2 mt-2">
                    @forelse ($categories as $category)
                        <span class="px-2.5 py-1 rounded border border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-[#161615] text-xs text-gray-800 dark:text-gray-200">
                            {{ $category->name }}
                        </span>
                    @empty
                        <span class="text-xs text-gray-400">{{ __('Soha ko\'rsatilmagan') }}</span>
                    @endforelse
                </div>
            </div>

            <div class="pt-4 border-t border-gray-100 dark:border-gray-800 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    @if ($providerRequest->status !== 'approved')
                        <form action="{{ route('admin.provider-requests.update', $providerRequest) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="approved">
                            <button type="submit"
                                class="px-4 py-2 text-xs font-semibold rounded-lg border border-accent/40 bg-accent/10 text-accent hover:bg-accent hover:text-white dark:hover:text-black hover:shadow-[0_0_15px_rgba(14,165,233,0.35)] transition cursor-pointer">
                                {{ __('approve_btn') }}
                            </button>
                        </form>
                    @endif

                    @if ($providerRequest->status !== 'rejected')
                        <form method="POST" action="{{ route('admin.provider-requests.update', $providerRequest) }}">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="rejected">
                            <button type="submit"
                                class="px-4 py-2 text-xs font-semibold rounded-lg border border-rose-500/30 bg-rose-500/10 text-rose-500 hover:bg-rose-500 hover:text-white transition cursor-pointer">
                                {{ __('reject_btn') }}
                            </button>
                        </form>
                    @endif
                </div>

                <form method="POST" action="{{ route('admin.provider-requests.destroy', $providerRequest) }}"
                    onsubmit="return confirm('{{ __('Haqiqatan ham bu arizani o\'chirmoqchimisiz?') }}')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-xs text-red-600 hover:text-red-800 dark:hover:text-red-400">
                        {{ __('delete_btn') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>