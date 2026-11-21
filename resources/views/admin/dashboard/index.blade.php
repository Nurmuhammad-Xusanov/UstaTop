<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-lg text-gray-900 dark:text-gray-100 tracking-tight">
                {{ __('admin_center') }}
            </h2>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300">
                    {{ __('system_active') }}
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="p-3 bg-gray-50 dark:bg-[#161615] border border-gray-200 dark:border-gray-800 text-gray-800 dark:text-gray-200 rounded text-xs">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="p-3 bg-red-50 dark:bg-red-950/40 border border-red-200 dark:border-red-900 text-red-800 dark:text-red-300 rounded text-xs">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Navigation Tabs for Admin -->
            <div class="flex flex-wrap gap-1.5 border-b border-gray-200 dark:border-gray-800 pb-2.5">
                <a href="{{ route('admin.dashboard') }}"
                    class="px-3 py-1.5 text-xs font-semibold rounded border border-accent/40 bg-accent/10 text-accent">
                    {{ __('general_stats') }}
                </a>
                <a href="{{ route('admin.users.index') }}"
                    class="px-3 py-1.5 text-xs font-medium rounded border border-transparent text-gray-600 dark:text-gray-400 hover:border-accent hover:text-accent dark:hover:border-accent dark:hover:text-accent hover:bg-accent/5 transition duration-200">
                    {{ __('users_tab') }} ({{ $stats['total_users'] }})
                </a>
                <a href="{{ route('admin.service-requests.index') }}"
                    class="px-3 py-1.5 text-xs font-medium rounded border border-transparent text-gray-600 dark:text-gray-400 hover:border-accent hover:text-accent dark:hover:border-accent dark:hover:text-accent hover:bg-accent/5 transition duration-200">
                    {{ __('orders_tab') }} ({{ $stats['total_requests'] }})
                </a>
                <a href="{{ route('admin.provider-requests.index') }}"
                    class="px-3 py-1.5 text-xs font-medium rounded border border-transparent text-gray-600 dark:text-gray-400 hover:border-accent hover:text-accent dark:hover:border-accent dark:hover:text-accent hover:bg-accent/5 transition duration-200 flex items-center gap-1.5">
                    {{ __('master_requests_tab') }}
                    @if ($stats['pending_provider_requests'] > 0)
                        <span class="px-1.5 py-0.2 text-[10px] bg-accent text-white rounded font-mono">
                            {{ $stats['pending_provider_requests'] }}
                        </span>
                    @endif
                </a>
                <a href="{{ route('admin.categories.index') }}"
                    class="px-3 py-1.5 text-xs font-medium rounded border border-transparent text-gray-600 dark:text-gray-400 hover:border-accent hover:text-accent dark:hover:border-accent dark:hover:text-accent hover:bg-accent/5 transition duration-200">
                    {{ __('categories_tab') }} ({{ $stats['total_categories'] }})
                </a>
                <a href="{{ route('admin.sessions.index') }}"
                    class="px-3 py-1.5 text-xs font-medium rounded border border-transparent text-gray-600 dark:text-gray-400 hover:border-accent hover:text-accent dark:hover:border-accent dark:hover:text-accent hover:bg-accent/5 transition duration-200">
                    {{ __('sessions_tab') }} ({{ $stats['active_sessions'] }})
                </a>
            </div>

            <!-- KPI Metric Cards Grid -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="p-4 bg-white dark:bg-[#111110] rounded-lg border border-gray-200 dark:border-gray-800">
                    <div class="text-[11px] font-medium text-gray-500 uppercase tracking-wider">{{ __('total_users') }}</div>
                    <div class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['total_users'] }}</div>
                    <div class="mt-0.5 text-[11px] text-gray-400">{{ $stats['total_clients'] }} {{ __('client') }} / {{ $stats['total_providers'] }} {{ __('provider') }}</div>
                </div>

                <div class="p-4 bg-white dark:bg-[#111110] rounded-lg border border-gray-200 dark:border-gray-800">
                    <div class="text-[11px] font-medium text-gray-500 uppercase tracking-wider">{{ __('qualified_masters') }}</div>
                    <div class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['total_providers'] }}</div>
                    <div class="mt-0.5 text-[11px] text-gray-400">{{ $stats['total_categories'] }} {{ __('categories_tab') }}</div>
                </div>

                <div class="p-4 bg-white dark:bg-[#111110] rounded-lg border border-gray-200 dark:border-gray-800">
                    <div class="text-[11px] font-medium text-gray-500 uppercase tracking-wider">{{ __('total_orders') }}</div>
                    <div class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['total_requests'] }}</div>
                    <div class="mt-0.5 text-[11px] text-gray-400">{{ $stats['completed_requests'] }} {{ __('status_completed') }}</div>
                </div>

                <div class="p-4 bg-white dark:bg-[#111110] rounded-lg border border-gray-200 dark:border-gray-800">
                    <div class="text-[11px] font-medium text-gray-500 uppercase tracking-wider">{{ __('pending_requests_kpi') }}</div>
                    <div class="mt-1 text-2xl font-bold {{ $stats['pending_provider_requests'] > 0 ? 'text-accent' : 'text-gray-900 dark:text-white' }}">
                        {{ $stats['pending_provider_requests'] }}
                    </div>
                    <div class="mt-0.5 text-[11px] text-gray-400">{{ __('master_requests_tab') }}</div>
                </div>
            </div>

            <!-- Two Column Overview: Recent Orders & Pending Master Applications -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Recent Orders -->
                <div class="p-5 bg-white dark:bg-[#111110] rounded-lg border border-gray-200 dark:border-gray-800">
                    <div class="flex items-center justify-between mb-3 pb-2 border-b border-gray-100 dark:border-gray-800">
                        <h3 class="text-sm font-semibold text-gray-900 dark:text-white">
                            {{ __('recent_orders') }}
                        </h3>
                        <a href="{{ route('admin.service-requests.index') }}" class="text-xs text-gray-500 hover:text-accent dark:hover:text-accent transition duration-200">
                            {{ __('view_all') }} &rarr;
                        </a>
                    </div>

                    <div class="divide-y divide-gray-100 dark:divide-gray-800/80">
                        @forelse ($recentRequests as $req)
                            <div class="py-2.5 flex items-center justify-between text-xs">
                                <div>
                                    <div class="font-medium text-gray-900 dark:text-white">
                                        #{{ $req->id }} &bull; {{ $req->category->name ?? 'Xizmat' }}
                                    </div>
                                    <div class="text-[11px] text-gray-400">
                                        {{ $req->city }} &bull; {{ $req->user->name ?? '' }}
                                    </div>
                                </div>
                                <div class="text-right">
                                    @php
                                        $statusClasses = match($req->status) {
                                            'completed' => 'text-emerald-700 bg-emerald-50 border-emerald-200 dark:text-emerald-300 dark:bg-emerald-950/40 dark:border-emerald-800',
                                            'accepted' => 'text-sky-700 bg-sky-50 border-sky-200 dark:text-sky-300 dark:bg-sky-950/40 dark:border-sky-800',
                                            'provider_done' => 'text-indigo-700 bg-indigo-50 border-indigo-200 dark:text-indigo-300 dark:bg-indigo-950/40 dark:border-indigo-800',
                                            'pending' => 'text-amber-700 bg-amber-50 border-amber-200 dark:text-amber-300 dark:bg-amber-950/40 dark:border-amber-800',
                                            'cancelled' => 'text-rose-700 bg-rose-50 border-rose-200 dark:text-rose-300 dark:bg-rose-950/40 dark:border-rose-800',
                                            default => 'text-gray-700 bg-gray-50 border-gray-200 dark:text-gray-300 dark:bg-neutral-900 dark:border-neutral-800',
                                        };
                                    @endphp
                                    <span class="inline-block px-2 py-0.5 rounded text-[10px] font-semibold uppercase tracking-wider border {{ $statusClasses }}">
                                        {{ $req->status }}
                                    </span>
                                    <div class="text-[10px] text-gray-400 mt-0.5">
                                        {{ $req->created_at->diffForHumans() }}
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-xs text-gray-500 py-3">{{ __('no_orders') }}</p>
                        @endforelse
                    </div>
                </div>

                <!-- Pending Master Applications -->
                <div class="p-5 bg-white dark:bg-[#111110] rounded-lg border border-gray-200 dark:border-gray-800">
                    <div class="flex items-center justify-between mb-3 pb-2 border-b border-gray-100 dark:border-gray-800">
                        <h3 class="text-sm font-semibold text-gray-900 dark:text-white">
                            {{ __('pending_master_apps') }}
                        </h3>
                        <a href="{{ route('admin.provider-requests.index') }}" class="text-xs text-gray-500 hover:text-accent dark:hover:text-accent transition duration-200">
                            {{ __('view_all') }} &rarr;
                        </a>
                    </div>

                    <div class="divide-y divide-gray-100 dark:divide-gray-800/80">
                        @forelse ($recentProviderRequests as $pr)
                            <div class="py-2.5 flex items-center justify-between text-xs">
                                <div>
                                    <div class="font-medium text-gray-900 dark:text-white">
                                        {{ $pr->user->name ?? '' }}
                                    </div>
                                    <div class="text-[11px] text-gray-400">
                                        {{ $pr->city }} &bull; {{ $pr->phone }}
                                    </div>
                                </div>
                                <a href="{{ route('admin.provider-requests.show', $pr->id) }}"
                                    class="px-2.5 py-1 text-xs rounded border border-accent/40 bg-accent/10 text-accent hover:bg-accent hover:text-white dark:hover:text-black hover:shadow-[0_0_15px_rgba(14,165,233,0.25)] transition duration-200">
                                    {{ __('review_btn') }}
                                </a>
                            </div>
                        @empty
                            <div class="py-4 text-center text-xs text-gray-400">
                                {{ __('no_pending_apps') }}
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Recent Users Section -->
            <div class="p-5 bg-white dark:bg-[#111110] rounded-lg border border-gray-200 dark:border-gray-800">
                <div class="flex items-center justify-between mb-3 pb-2 border-b border-gray-100 dark:border-gray-800">
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white">
                        {{ __('new_users') }}
                    </h3>
                    <a href="{{ route('admin.users.index') }}" class="text-xs text-gray-500 hover:text-accent dark:hover:text-accent transition duration-200">
                        {{ __('users_mgmt') }} &rarr;
                    </a>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2.5 text-xs">
                    @foreach ($recentUsers as $ru)
                        <div class="p-2.5 rounded border border-gray-100 dark:border-gray-800 flex items-center justify-between">
                            <div>
                                <div class="font-medium text-gray-900 dark:text-white">{{ $ru->name }}</div>
                                <div class="text-[11px] text-gray-400 truncate max-w-[160px]">{{ $ru->email }}</div>
                            </div>
                            <span class="text-[10px] px-2 py-0.5 rounded font-mono font-medium uppercase border border-gray-200 dark:border-gray-800 text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-neutral-900/60">
                                {{ $ru->role }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
