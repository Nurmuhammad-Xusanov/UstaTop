<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Admin Boshqaruv Markazi') }}
            </h2>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300">
                    <span class="w-2 h-2 mr-1.5 bg-green-500 rounded-full animate-pulse"></span>
                    {{ __('Tizim Faol') }}
                </span>
                <span class="text-xs text-gray-500 dark:text-gray-400">
                    {{ __('PostgreSQL & Telegram Webhook') }}
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            @if (session('success'))
                <div class="p-4 bg-green-100 dark:bg-green-900/30 border border-green-300 dark:border-green-700 text-green-800 dark:text-green-300 rounded-lg text-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="p-4 bg-red-100 dark:bg-red-900/30 border border-red-300 dark:border-red-700 text-red-800 dark:text-red-300 rounded-lg text-sm">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Navigation Tabs for Admin -->
            <div class="flex flex-wrap gap-2 border-b border-gray-200 dark:border-gray-800 pb-3">
                <a href="{{ route('admin.dashboard') }}"
                    class="px-4 py-2 text-sm font-semibold rounded-md bg-black text-white dark:bg-white dark:text-black">
                    📊 {{ __('Umumiy statistika') }}
                </a>
                <a href="{{ route('admin.users.index') }}"
                    class="px-4 py-2 text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-[#1a1a19] transition">
                    👥 {{ __('Foydalanuvchilar') }} ({{ $stats['total_users'] }})
                </a>
                <a href="{{ route('admin.service-requests.index') }}"
                    class="px-4 py-2 text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-[#1a1a19] transition">
                    🛠 {{ __('Buyurtmalar') }} ({{ $stats['total_requests'] }})
                </a>
                <a href="{{ route('admin.provider-requests.index') }}"
                    class="px-4 py-2 text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-[#1a1a19] transition flex items-center gap-1.5">
                    🪪 {{ __('Usta arizalari') }}
                    @if ($stats['pending_provider_requests'] > 0)
                        <span class="px-1.5 py-0.5 text-xs bg-red-500 text-white rounded-full font-bold">
                            {{ $stats['pending_provider_requests'] }}
                        </span>
                    @endif
                </a>
                <a href="{{ route('admin.categories.index') }}"
                    class="px-4 py-2 text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-[#1a1a19] transition">
                    🗂 {{ __('Kategoriyalar') }} ({{ $stats['total_categories'] }})
                </a>
                <a href="{{ route('admin.sessions.index') }}"
                    class="px-4 py-2 text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-[#1a1a19] transition">
                    🔒 {{ __('Aktiv Sessiyalar') }} ({{ $stats['active_sessions'] }})
                </a>
            </div>

            <!-- KPI Metric Cards Grid -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="p-5 bg-white dark:bg-[#161615] rounded-xl border border-gray-100 dark:border-gray-800 shadow-sm">
                    <div class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Jami Foydalanuvchilar') }}</div>
                    <div class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['total_users'] }}</div>
                    <div class="mt-1 text-xs text-gray-500">{{ $stats['total_clients'] }} mijoz / {{ $stats['total_providers'] }} usta</div>
                </div>

                <div class="p-5 bg-white dark:bg-[#161615] rounded-xl border border-gray-100 dark:border-gray-800 shadow-sm">
                    <div class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Malakali Ustalar') }}</div>
                    <div class="mt-2 text-3xl font-bold text-accent">{{ $stats['total_providers'] }}</div>
                    <div class="mt-1 text-xs text-gray-500">{{ $stats['total_categories'] }} ta xizmat sohasi</div>
                </div>

                <div class="p-5 bg-white dark:bg-[#161615] rounded-xl border border-gray-100 dark:border-gray-800 shadow-sm">
                    <div class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Jami Buyurtmalar') }}</div>
                    <div class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['total_requests'] }}</div>
                    <div class="mt-1 text-xs text-green-600 dark:text-green-400 font-medium">{{ $stats['completed_requests'] }} tasi muvaffaqiyatli</div>
                </div>

                <div class="p-5 bg-white dark:bg-[#161615] rounded-xl border border-gray-100 dark:border-gray-800 shadow-sm">
                    <div class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Kutilayotgan Arizalar') }}</div>
                    <div class="mt-2 text-3xl font-bold {{ $stats['pending_provider_requests'] > 0 ? 'text-amber-500' : 'text-gray-900 dark:text-white' }}">
                        {{ $stats['pending_provider_requests'] }}
                    </div>
                    <div class="mt-1 text-xs text-gray-500">Usta bo'lish uchun kutayotganlar</div>
                </div>
            </div>

            <!-- Two Column Overview: Recent Orders & Pending Master Applications -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Recent Orders -->
                <div class="p-6 bg-white dark:bg-[#161615] rounded-xl border border-gray-100 dark:border-gray-800 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-base font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                            <span>🛠</span> {{ __('So\'nggi Buyurtmalar') }}
                        </h3>
                        <a href="{{ route('admin.service-requests.index') }}" class="text-xs text-accent hover:underline">
                            {{ __('Barchasini ko\'rish') }} &rarr;
                        </a>
                    </div>

                    <div class="divide-y divide-gray-100 dark:divide-gray-800">
                        @forelse ($recentRequests as $req)
                            <div class="py-3 flex items-center justify-between">
                                <div>
                                    <div class="text-sm font-medium text-gray-900 dark:text-white flex items-center gap-2">
                                        <span>#{{ $req->id }}</span>
                                        <span>{{ $req->category->name ?? 'Xizmat' }}</span>
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ $req->city ?? 'Noma\'lum shahar' }} &bull; {{ $req->user->name ?? 'Mijoz' }}
                                    </div>
                                </div>
                                <div class="text-right">
                                    @php
                                        $badgeClasses = match($req->status) {
                                            'completed' => 'bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-300',
                                            'accepted', 'provider_done' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-300',
                                            'cancelled' => 'bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-300',
                                            default => 'bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300',
                                        };
                                    @endphp
                                    <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-medium {{ $badgeClasses }}">
                                        {{ $req->status }}
                                    </span>
                                    <div class="text-[11px] text-gray-400 mt-1">
                                        {{ $req->created_at->diffForHumans() }}
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500 py-4">{{ __('Hozircha buyurtmalar yo\'q.') }}</p>
                        @endforelse
                    </div>
                </div>

                <!-- Pending Master Applications -->
                <div class="p-6 bg-white dark:bg-[#161615] rounded-xl border border-gray-100 dark:border-gray-800 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-base font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                            <span>🪪</span> {{ __('Kutilayotgan Usta Arizalari') }}
                        </h3>
                        <a href="{{ route('admin.provider-requests.index') }}" class="text-xs text-accent hover:underline">
                            {{ __('Barchasi') }} &rarr;
                        </a>
                    </div>

                    <div class="divide-y divide-gray-100 dark:divide-gray-800">
                        @forelse ($recentProviderRequests as $pr)
                            <div class="py-3 flex items-center justify-between">
                                <div>
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $pr->user->name ?? 'Foydalanuvchi' }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        📍 {{ $pr->city }} &bull; 📞 {{ $pr->phone }}
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.provider-requests.show', $pr->id) }}"
                                        class="px-2.5 py-1 text-xs rounded bg-black dark:bg-white text-white dark:text-black font-medium hover:opacity-80 transition">
                                        {{ __('Ko\'rib chiqish') }}
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="py-6 text-center text-sm text-gray-500">
                                ✅ {{ __('Yangi ko\'rib chiqilmagan arizalar yo\'q.') }}
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Recent Users Section -->
            <div class="p-6 bg-white dark:bg-[#161615] rounded-xl border border-gray-100 dark:border-gray-800 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-base font-semibold text-gray-900 dark:text-white">
                        👥 {{ __('Yangi Ro\'yxatdan O\'tgan Foydalanuvchilar') }}
                    </h3>
                    <a href="{{ route('admin.users.index') }}" class="text-xs text-accent hover:underline">
                        {{ __('Foydalanuvchilar boshqaruvi') }} &rarr;
                    </a>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                    @foreach ($recentUsers as $ru)
                        <div class="p-3 rounded-lg border border-gray-100 dark:border-gray-800 flex items-center justify-between">
                            <div>
                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $ru->name }}</div>
                                <div class="text-xs text-gray-500 truncate max-w-[180px]">{{ $ru->email }}</div>
                            </div>
                            <span class="text-[11px] px-2 py-0.5 rounded font-mono uppercase {{ $ru->role === 'admin' ? 'bg-purple-100 text-purple-700 dark:bg-purple-900/50 dark:text-purple-300' : ($ru->role === 'provider' ? 'bg-amber-100 text-amber-700 dark:bg-amber-900/50 dark:text-amber-300' : 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300') }}">
                                {{ $ru->role }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
