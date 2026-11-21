<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white tracking-tight">
                {{ __('master_requests_tab') }}
            </h2>
            <a href="{{ route('admin.dashboard') }}" class="text-xs text-gray-500 hover:text-black dark:hover:text-white transition">
                &larr; {{ __('Dashboardga qaytish') }}
            </a>
        </div>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
        @if (session('success'))
            <div class="p-3 bg-gray-50 dark:bg-[#161615] border border-gray-200 dark:border-gray-800 text-gray-800 dark:text-gray-200 rounded text-xs">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white dark:bg-[#111110] rounded-lg border border-gray-200 dark:border-gray-800 overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="border-b border-gray-200 dark:border-gray-800 bg-gray-50/70 dark:bg-[#161615] text-[11px] text-gray-500 uppercase tracking-wider">
                            <th class="py-3 px-4 font-semibold w-1/4">{{ __('user_col') }}</th>
                            <th class="py-3 px-4 font-semibold w-1/4">{{ __('city_col') }}</th>
                            <th class="py-3 px-4 font-semibold w-1/6">{{ __('phone_col') }}</th>
                            <th class="py-3 px-4 font-semibold w-1/6">{{ __('status_col') }}</th>
                            <th class="py-3 px-4 font-semibold text-right w-1/6">{{ __('actions_col') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800/80">
                        @forelse ($requests as $r)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-[#1a1a19]/50 transition">
                                <td class="py-3 px-4 font-medium text-gray-900 dark:text-white">
                                    {{ $r->user->name ?? 'Unknown' }}
                                    <div class="text-[11px] text-gray-400 font-normal">{{ $r->user->email ?? '' }}</div>
                                </td>
                                <td class="py-3 px-4 text-gray-700 dark:text-gray-300">
                                    {{ $r->city }}
                                </td>
                                <td class="py-3 px-4 text-gray-600 dark:text-gray-400 font-mono">
                                    {{ $r->phone }}
                                </td>
                                <td class="py-3 px-4">
                                    <span class="inline-block px-2 py-0.5 rounded text-[10px] font-medium uppercase tracking-wider border border-gray-200 dark:border-gray-800 {{ $r->status === 'approved' ? 'text-green-600 dark:text-green-400' : ($r->status === 'rejected' ? 'text-red-600 dark:text-red-400' : 'text-amber-600 dark:text-amber-400') }}">
                                        {{ $r->status }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-right">
                                    <a href="{{ route('admin.provider-requests.show', $r) }}"
                                        class="px-2.5 py-1 text-xs rounded border border-gray-200 dark:border-gray-800 hover:border-black dark:hover:border-white transition">
                                        {{ __('details_btn') }}
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-6 text-center text-gray-500">
                                    {{ __('no_pending_apps') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>