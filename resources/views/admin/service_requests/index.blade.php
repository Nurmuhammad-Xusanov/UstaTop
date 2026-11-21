<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Buyurtmalar Boshqaruvi') }}
            </h2>
            <a href="{{ route('admin.dashboard') }}" class="text-xs text-accent hover:underline">
                &larr; {{ __('Dashboardga qaytish') }}
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="p-4 bg-green-100 dark:bg-green-900/30 border border-green-300 dark:border-green-700 text-green-800 dark:text-green-300 rounded-lg text-sm">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Filter Bar -->
            <div class="p-4 bg-white dark:bg-[#161615] rounded-xl border border-gray-100 dark:border-gray-800 shadow-sm flex flex-wrap gap-4 items-center justify-between">
                <form method="GET" action="{{ route('admin.service-requests.index') }}" class="flex flex-wrap gap-3 items-center flex-1">
                    <select name="status"
                        class="px-3.5 py-2 text-sm rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-[#1a1a19] text-gray-900 dark:text-white focus:ring-accent">
                        <option value="">{{ __('Barcha holatlar') }}</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending (Kutilmoqda)</option>
                        <option value="accepted" {{ request('status') === 'accepted' ? 'selected' : '' }}>Accepted (Qabul qilingan)</option>
                        <option value="provider_done" {{ request('status') === 'provider_done' ? 'selected' : '' }}>Provider Done (Bajarilgan)</option>
                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed (Yakunlangan)</option>
                        <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled (Bekor qilingan)</option>
                    </select>

                    <select name="category_id"
                        class="px-3.5 py-2 text-sm rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-[#1a1a19] text-gray-900 dark:text-white focus:ring-accent">
                        <option value="">{{ __('Barcha kategoriyalar') }}</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>

                    <button type="submit"
                        class="px-4 py-2 text-sm font-semibold rounded-lg bg-black text-white dark:bg-white dark:text-black hover:opacity-90 transition">
                        {{ __('Filtrlash') }}
                    </button>

                    @if(request()->hasAny(['status', 'category_id']))
                        <a href="{{ route('admin.service-requests.index') }}" class="text-xs text-gray-500 hover:underline">
                            {{ __('Filtrni tozalash') }}
                        </a>
                    @endif
                </form>

                <div class="text-xs text-gray-500">
                    {{ __('Jami') }}: <span class="font-bold text-gray-900 dark:text-white">{{ $requests->total() }}</span> {{ __('ta buyurtma') }}
                </div>
            </div>

            <!-- Requests Table -->
            <div class="bg-white dark:bg-[#161615] rounded-xl border border-gray-100 dark:border-gray-800 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-sm">
                        <thead>
                            <tr class="border-b border-gray-200 dark:border-gray-800 bg-gray-50/50 dark:bg-[#1f1f1d]/50 text-xs text-gray-500 uppercase tracking-wider">
                                <th class="py-3.5 px-4 font-semibold">{{ __('ID') }}</th>
                                <th class="py-3.5 px-4 font-semibold">{{ __('Mijoz') }}</th>
                                <th class="py-3.5 px-4 font-semibold">{{ __('Xizmat Sohasi') }}</th>
                                <th class="py-3.5 px-4 font-semibold">{{ __('Usta (Ijrochi)') }}</th>
                                <th class="py-3.5 px-4 font-semibold">{{ __('Shahar & Tel') }}</th>
                                <th class="py-3.5 px-4 font-semibold">{{ __('Holat') }}</th>
                                <th class="py-3.5 px-4 font-semibold">{{ __('Sana') }}</th>
                                <th class="py-3.5 px-4 font-semibold text-right">{{ __('Amal') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                            @forelse ($requests as $req)
                                <tr class="hover:bg-gray-50/50 dark:hover:bg-[#1a1a19]/50 transition">
                                    <td class="py-3 px-4 font-mono text-xs text-gray-400">#{{ $req->id }}</td>
                                    <td class="py-3 px-4">
                                        <div class="font-medium text-gray-900 dark:text-white">{{ $req->user->name ?? __('Noma\'lum') }}</div>
                                        <div class="text-xs text-gray-500">{{ $req->email ?? $req->user->email ?? '' }}</div>
                                    </td>
                                    <td class="py-3 px-4">
                                        <span class="inline-block px-2.5 py-0.5 text-xs rounded bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-200 font-medium">
                                            {{ $req->category->name ?? __('Mavjud emas') }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4">
                                        @if ($req->provider)
                                            <div class="font-medium text-gray-900 dark:text-white">{{ $req->provider->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $req->provider->email }}</div>
                                        @else
                                            <span class="text-xs text-amber-600 dark:text-amber-400 font-medium">{{ __('Biriktirilmagan') }}</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4">
                                        <div class="text-xs text-gray-900 dark:text-white font-medium">{{ $req->city }}</div>
                                        <div class="text-xs text-gray-500">{{ $req->phone }}</div>
                                    </td>
                                    <td class="py-3 px-4">
                                        <form method="POST" action="{{ route('admin.service-requests.status', $req->id) }}" class="inline-flex items-center">
                                            @csrf
                                            @method('PATCH')
                                            <select name="status" onchange="this.form.submit()"
                                                class="text-xs py-1 px-2 rounded border border-gray-200 dark:border-gray-700 bg-white dark:bg-[#1a1a19] text-gray-900 dark:text-white focus:ring-accent font-medium">
                                                <option value="pending" {{ $req->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="accepted" {{ $req->status === 'accepted' ? 'selected' : '' }}>Accepted</option>
                                                <option value="provider_done" {{ $req->status === 'provider_done' ? 'selected' : '' }}>Provider Done</option>
                                                <option value="completed" {{ $req->status === 'completed' ? 'selected' : '' }}>Completed</option>
                                                <option value="cancelled" {{ $req->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                            </select>
                                        </form>
                                    </td>
                                    <td class="py-3 px-4 text-xs text-gray-500">
                                        {{ $req->created_at->format('d.m.Y H:i') }}
                                    </td>
                                    <td class="py-3 px-4 text-right">
                                        <form method="POST" action="{{ route('admin.service-requests.destroy', $req->id) }}"
                                            onsubmit="return confirm('{{ __('Haqiqatan ham bu buyurtmani o\'chirmoqchimisiz?') }}')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-xs text-red-600 hover:text-red-800 font-medium">
                                                {{ __('O\'chirish') }}
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="py-8 text-center text-sm text-gray-500">
                                        {{ __('Hech qanday buyurtma topilmadi.') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($requests->hasPages())
                    <div class="p-4 border-t border-gray-100 dark:border-gray-800">
                        {{ $requests->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
