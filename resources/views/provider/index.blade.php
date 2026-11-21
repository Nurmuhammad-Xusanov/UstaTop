<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white tracking-tight">
                {{ __('Usta bo\'lish') }}
            </h2>
            @if (!$hasPending && auth()->user()->role !== 'provider')
                <a href="{{ route('provider-requests.create') }}"
                    class="px-3.5 py-1.5 text-xs font-semibold rounded bg-black text-white dark:bg-white dark:text-black hover:opacity-85 transition">
                    {{ __('Yangi ariza topshirish') }}
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
        @if (session('success'))
            <div class="p-3 bg-gray-50 dark:bg-[#161615] border border-gray-200 dark:border-gray-800 text-gray-800 dark:text-gray-200 rounded text-xs">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="p-3 bg-red-50 dark:bg-red-950/40 border border-red-200 dark:border-red-900 text-red-800 dark:text-red-300 rounded text-xs">
                @foreach ($errors->all() as $err)
                    <p>{{ $err }}</p>
                @endforeach
            </div>
        @endif

        @if (auth()->user()->role === 'provider')
            <div class="p-4 rounded-lg border border-gray-200 dark:border-gray-800 bg-white dark:bg-[#111110] text-xs">
                <span class="font-semibold text-green-600 dark:text-green-400">✓ {{ __('Siz UstaTop platformasida tasdiqlangan ustasiz.') }}</span>
                <p class="text-gray-500 mt-1">{{ __('Yangi buyurtmalarni "Xizmatlar" bo\'limidan qabul qilishingiz mumkin.') }}</p>
            </div>
        @endif

        <div class="bg-white dark:bg-[#111110] rounded-lg border border-gray-200 dark:border-gray-800 overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="border-b border-gray-200 dark:border-gray-800 bg-gray-50/70 dark:bg-[#161615] text-[11px] text-gray-500 uppercase tracking-wider">
                            <th class="py-3 px-4 font-semibold w-1/4">{{ __('city_col') }}</th>
                            <th class="py-3 px-4 font-semibold w-1/4">{{ __('phone_col') }}</th>
                            <th class="py-3 px-4 font-semibold w-1/4">{{ __('status_col') }}</th>
                            <th class="py-3 px-4 font-semibold text-right w-1/4">{{ __('actions_col') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800/80">
                        @forelse ($requests as $request)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-[#1a1a19]/50 transition">
                                <td class="py-3 px-4 text-gray-900 dark:text-white font-medium">
                                    {{ $request->city }}
                                </td>
                                <td class="py-3 px-4 text-gray-600 dark:text-gray-400 font-mono">
                                    {{ $request->phone }}
                                </td>
                                <td class="py-3 px-4">
                                    <span class="inline-block px-2 py-0.5 rounded text-[10px] font-medium uppercase tracking-wider border border-gray-200 dark:border-gray-800 {{ $request->status === 'approved' ? 'text-green-600 dark:text-green-400' : ($request->status === 'rejected' ? 'text-red-600 dark:text-red-400' : 'text-amber-600 dark:text-amber-400') }}">
                                        {{ $request->status }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-right">
                                    @if ($request->status === 'pending')
                                        <form method="POST" action="{{ route('provider-requests.destroy', $request) }}"
                                            onsubmit="return confirm('Arizani bekor qilmoqchimisiz?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-xs text-red-600 hover:text-red-800 dark:hover:text-red-400">
                                                {{ __('delete_btn') }}
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-gray-400 text-xs">—</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-6 text-center text-gray-500">
                                    {{ __('Hozircha arizalar yo\'q.') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
