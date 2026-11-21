<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Aktiv Sessiyalar va Xavfsizlik') }}
            </h2>
            <a href="{{ route('admin.dashboard') }}" class="text-xs text-accent hover:underline">
                &larr; {{ __('Dashboardga qaytish') }}
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="p-5 bg-white dark:bg-[#161615] rounded-xl border border-gray-100 dark:border-gray-800 shadow-sm flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white">
                        🔒 {{ __('Tizimdagi real-vaqt sessiyalari') }}
                    </h3>
                    <p class="text-xs text-gray-500 mt-1">
                        PostgreSQL `sessions` jadvalida saqlangan foydalanuvchilar va mehmonlarning faol ulanishlari.
                    </p>
                </div>
                <div class="text-right">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-300">
                        Jami: {{ $sessions->total() }} ta sessiya
                    </span>
                </div>
            </div>

            <div class="bg-white dark:bg-[#161615] rounded-xl border border-gray-100 dark:border-gray-800 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-sm">
                        <thead>
                            <tr class="border-b border-gray-200 dark:border-gray-800 bg-gray-50/50 dark:bg-[#1f1f1d]/50 text-xs text-gray-500 uppercase tracking-wider">
                                <th class="py-3.5 px-4 font-semibold">Sessiya ID</th>
                                <th class="py-3.5 px-4 font-semibold">Foydalanuvchi</th>
                                <th class="py-3.5 px-4 font-semibold">IP Manzil</th>
                                <th class="py-3.5 px-4 font-semibold">Qurilma / Brauzer</th>
                                <th class="py-3.5 px-4 font-semibold">So'nggi faollik</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                            @forelse ($sessions as $s)
                                <tr class="hover:bg-gray-50/50 dark:hover:bg-[#1a1a19]/50 transition">
                                    <td class="py-3 px-4 font-mono text-xs text-gray-400">
                                        {{ substr($s->id, 0, 14) }}...
                                    </td>
                                    <td class="py-3 px-4">
                                        @if ($s->user_name)
                                            <div class="font-medium text-gray-900 dark:text-white">{{ $s->user_name }}</div>
                                            <div class="text-xs text-gray-500">{{ $s->user_email }}</div>
                                        @else
                                            <span class="text-xs text-gray-400 italic">Mehmon (Guest)</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4 font-mono text-xs text-gray-700 dark:text-gray-300">
                                        {{ $s->ip_address ?? '127.0.0.1' }}
                                    </td>
                                    <td class="py-3 px-4 text-xs text-gray-600 dark:text-gray-400 max-w-xs truncate" title="{{ $s->user_agent }}">
                                        {{ $s->user_agent ?? 'Noma\'lum' }}
                                    </td>
                                    <td class="py-3 px-4 text-xs text-gray-500">
                                        {{ \Carbon\Carbon::createFromTimestamp($s->last_activity)->diffForHumans() }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-8 text-center text-sm text-gray-500">
                                        Hozircha faol sessiyalar mavjud emas.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($sessions->hasPages())
                    <div class="p-4 border-t border-gray-100 dark:border-gray-800">
                        {{ $sessions->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
