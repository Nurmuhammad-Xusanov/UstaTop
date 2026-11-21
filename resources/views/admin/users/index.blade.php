<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Foydalanuvchilar Boshqaruvi') }}
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

            @if (session('error'))
                <div class="p-4 bg-red-100 dark:bg-red-900/30 border border-red-300 dark:border-red-700 text-red-800 dark:text-red-300 rounded-lg text-sm">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Search and Filter Bar -->
            <div class="p-4 bg-white dark:bg-[#161615] rounded-xl border border-gray-100 dark:border-gray-800 shadow-sm flex flex-wrap gap-4 items-center justify-between">
                <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-wrap gap-3 items-center flex-1">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="{{ __('Ism yoki email bo\'yicha qidirish...') }}"
                        class="px-3.5 py-2 text-sm rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-[#1a1a19] text-gray-900 dark:text-white focus:ring-accent focus:border-accent min-w-[260px]">

                    <select name="role"
                        class="px-3.5 py-2 text-sm rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-[#1a1a19] text-gray-900 dark:text-white focus:ring-accent focus:border-accent">
                        <option value="" class="bg-white dark:bg-[#1a1a19] text-gray-900 dark:text-gray-100">{{ __('Barcha rollar') }}</option>
                        <option value="client" class="bg-white dark:bg-[#1a1a19] text-gray-900 dark:text-gray-100" {{ request('role') === 'client' ? 'selected' : '' }}>{{ __('Client (Mijoz)') }}</option>
                        <option value="provider" class="bg-white dark:bg-[#1a1a19] text-gray-900 dark:text-gray-100" {{ request('role') === 'provider' ? 'selected' : '' }}>{{ __('Provider (Usta)') }}</option>
                        <option value="admin" class="bg-white dark:bg-[#1a1a19] text-gray-900 dark:text-gray-100" {{ request('role') === 'admin' ? 'selected' : '' }}>{{ __('Admin') }}</option>
                    </select>

                    <button type="submit"
                        class="px-4 py-2 text-sm font-semibold rounded-lg border border-accent/40 bg-accent/10 text-accent hover:bg-accent hover:text-white dark:hover:text-black hover:shadow-[0_0_15px_rgba(14,165,233,0.35)] transition cursor-pointer">
                        {{ __('Filtrlash') }}
                    </button>

                    @if(request()->hasAny(['search', 'role']))
                        <a href="{{ route('admin.users.index') }}" class="text-xs text-gray-500 hover:underline">
                            {{ __('Filtrni tozalash') }}
                        </a>
                    @endif
                </form>

                <div class="text-xs text-gray-500">
                    {{ __('Jami') }}: <span class="font-bold text-gray-900 dark:text-white">{{ $users->total() }}</span> {{ __('ta foydalanuvchi') }}
                </div>
            </div>

            <!-- Users Table -->
            <div class="bg-white dark:bg-[#161615] rounded-xl border border-gray-100 dark:border-gray-800 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-sm">
                        <thead>
                            <tr class="border-b border-gray-200 dark:border-gray-800 bg-gray-50/50 dark:bg-[#1f1f1d]/50 text-xs text-gray-500 uppercase tracking-wider">
                                <th class="py-3.5 px-4 font-semibold">{{ __('ID') }}</th>
                                <th class="py-3.5 px-4 font-semibold">{{ __('Foydalanuvchi') }}</th>
                                <th class="py-3.5 px-4 font-semibold">{{ __('Rol') }}</th>
                                <th class="py-3.5 px-4 font-semibold">{{ __('Email Tasdiq') }}</th>
                                <th class="py-3.5 px-4 font-semibold">{{ __('Telegram') }}</th>
                                <th class="py-3.5 px-4 font-semibold">{{ __('Ro\'yxatdan o\'tgan') }}</th>
                                <th class="py-3.5 px-4 font-semibold text-right">{{ __('Amallar') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                            @forelse ($users as $user)
                                <tr class="hover:bg-gray-50/50 dark:hover:bg-[#1a1a19]/50 transition">
                                    <td class="py-3 px-4 font-mono text-xs text-gray-400">#{{ $user->id }}</td>
                                    <td class="py-3 px-4">
                                        <div class="font-medium text-gray-900 dark:text-white">{{ $user->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $user->email }}</div>
                                        @if($user->categories->isNotEmpty())
                                            <div class="mt-1 flex flex-wrap gap-1">
                                                @foreach($user->categories as $c)
                                                    <span class="text-[10px] px-1.5 py-0.5 rounded bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300">
                                                        {{ $c->name }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4">
                                        <form method="POST" action="{{ route('admin.users.role', $user->id) }}" class="inline-flex items-center gap-1">
                                            @csrf
                                            @method('PATCH')
                                            <select name="role" onchange="this.form.submit()"
                                                class="text-xs py-1 px-2 rounded border border-gray-200 dark:border-gray-700 bg-white dark:bg-[#1a1a19] text-gray-900 dark:text-white focus:ring-accent">
                                                <option value="client" class="bg-white dark:bg-[#1a1a19] text-gray-900 dark:text-gray-100" {{ $user->role === 'client' ? 'selected' : '' }}>{{ __('Client (Mijoz)') }}</option>
                                                <option value="provider" class="bg-white dark:bg-[#1a1a19] text-gray-900 dark:text-gray-100" {{ $user->role === 'provider' ? 'selected' : '' }}>{{ __('Provider (Usta)') }}</option>
                                                <option value="admin" class="bg-white dark:bg-[#1a1a19] text-gray-900 dark:text-gray-100" {{ $user->role === 'admin' ? 'selected' : '' }}>{{ __('Admin') }}</option>
                                            </select>
                                        </form>
                                    </td>
                                    <td class="py-3 px-4">
                                        <form method="POST" action="{{ route('admin.users.toggle-verify', $user->id) }}">
                                            @csrf
                                            @method('PATCH')
                                            @if ($user->email_verified_at)
                                                <button type="submit" title="{{ __('Tasdiqni bekor qilish') }}"
                                                    class="inline-flex items-center gap-1 text-xs px-2 py-0.5 rounded border border-green-200 dark:border-green-800 bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-300 hover:opacity-80">
                                                    <span>✓</span> {{ __('Tasdiqlangan') }}
                                                </button>
                                            @else
                                                <button type="submit" title="{{ __('Emailni tasdiqlash') }}"
                                                    class="inline-flex items-center gap-1 text-xs px-2 py-0.5 rounded border border-amber-200 dark:border-amber-800 bg-amber-50 dark:bg-amber-900/20 text-amber-700 dark:text-amber-300 hover:opacity-80">
                                                    <span>✕</span> {{ __('Tasdiqlanmagan') }}
                                                </button>
                                            @endif
                                        </form>
                                    </td>
                                    <td class="py-3 px-4">
                                        @if ($user->telegram_id)
                                            <span class="inline-flex items-center gap-1 text-xs font-mono text-gray-700 dark:text-gray-300">
                                                <span class="text-[10px] font-semibold px-1 py-0.5 rounded bg-gray-100 dark:bg-gray-800">TG</span> {{ $user->telegram_id }}
                                            </span>
                                        @else
                                            <span class="text-xs text-gray-400">{{ __('Ulanmagan') }}</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4 text-xs text-gray-500">
                                        {{ $user->created_at->format('d.m.Y H:i') }}
                                    </td>
                                    <td class="py-3 px-4 text-right">
                                        @if ($user->id !== auth()->id())
                                            <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}"
                                                onsubmit="return confirm('{{ __('Haqiqatan ham bu foydalanuvchini o\'chirmoqchimisiz?') }}')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-xs text-red-600 hover:text-red-800 dark:hover:text-red-400 font-medium">
                                                    {{ __('O\'chirish') }}
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-xs text-gray-400">({{ __('Siz') }})</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-8 text-center text-sm text-gray-500">
                                        {{ __('Hech qanday foydalanuvchi topilmadi.') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($users->hasPages())
                    <div class="p-4 border-t border-gray-100 dark:border-gray-800">
                        {{ $users->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
