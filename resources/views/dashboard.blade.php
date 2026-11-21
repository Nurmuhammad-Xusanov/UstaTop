<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-gray-900 dark:text-white tracking-tight">
                    {{ __('Xush kelibsiz, :name!', ['name' => auth()->user()->name]) }}
                </h2>
                <p class="text-xs text-gray-500 mt-0.5">
                    {{ __('UstaTop boshqaruv paneli orqali xizmatlarni boshqaring.') }}
                </p>
            </div>
            <span class="px-3 py-1 rounded-full text-xs font-semibold uppercase tracking-wider border border-accent/40 bg-accent/10 text-accent">
                {{ auth()->user()->role === 'admin' ? __('Administrator') : (auth()->user()->role === 'provider' ? __('Usta') : __('Mijoz')) }}
            </span>
        </div>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        @if (!auth()->user()->telegram_id)
            <div class="p-4 rounded-xl border border-sky-200/60 dark:border-sky-900/40 bg-sky-50/60 dark:bg-sky-950/20 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-lg bg-sky-500/10 text-sky-500 flex items-center justify-center font-bold text-sm">
                        TG
                    </div>
                    <div>
                        <div class="text-sm font-semibold text-gray-900 dark:text-white">
                            {{ __('Telegram bot orqali tezkor bildirishnomalarni yoqing') }}
                        </div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">
                            {{ __('Buyurtmalaringiz holati o\'zgarganda darhol xabardor bo\'ling: @ustatpuzbot') }}
                        </div>
                    </div>
                </div>
                <a href="https://t.me/{{ config('services.telegram.bot_username', 'ustatpuzbot') }}?start={{ auth()->id() }}" target="_blank"
                    class="inline-flex items-center justify-center px-4 py-2 text-xs font-semibold rounded-lg border border-accent/40 bg-accent/10 text-accent hover:bg-accent hover:text-white dark:hover:text-black transition">
                    {{ __('Botni ochish') }} &rarr;
                </a>
            </div>
        @endif

        <!-- Quick Action Cards Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <a href="{{ route('service-requests.create') }}"
                class="group p-5 rounded-2xl border border-gray-200/70 dark:border-gray-800 bg-white dark:bg-[#111110] hover:border-accent hover:shadow-[0_0_25px_rgba(14,165,233,0.15)] transition-all">
                <div class="w-10 h-10 rounded-xl bg-accent/10 text-accent flex items-center justify-center font-bold text-lg mb-3 group-hover:scale-105 transition-transform">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                </div>
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white group-hover:text-accent transition-colors">
                    {{ __('Yangi buyurtma') }}
                </h3>
                <p class="text-xs text-gray-500 mt-1">
                    {{ __('Usta chaqirish uchun yangi so\'rov yuborish') }}
                </p>
            </a>

            <a href="{{ route('service-requests.index') }}"
                class="group p-5 rounded-2xl border border-gray-200/70 dark:border-gray-800 bg-white dark:bg-[#111110] hover:border-accent hover:shadow-[0_0_25px_rgba(14,165,233,0.15)] transition-all">
                <div class="w-10 h-10 rounded-xl bg-indigo-500/10 text-indigo-500 dark:text-indigo-400 flex items-center justify-center font-bold text-base mb-3 group-hover:scale-105 transition-transform">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white group-hover:text-accent transition-colors">
                    {{ __('Buyurtmalarim') }}
                </h3>
                <p class="text-xs text-gray-500 mt-1">
                    {{ __('Faol va yakunlangan buyurtmalar holati') }}
                </p>
            </a>

            <a href="{{ route('provider-requests.index') }}"
                class="group p-5 rounded-2xl border border-gray-200/70 dark:border-gray-800 bg-white dark:bg-[#111110] hover:border-accent hover:shadow-[0_0_25px_rgba(14,165,233,0.15)] transition-all">
                <div class="w-10 h-10 rounded-xl bg-emerald-500/10 text-emerald-500 dark:text-emerald-400 flex items-center justify-center font-bold text-base mb-3 group-hover:scale-105 transition-transform">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white group-hover:text-accent transition-colors">
                    {{ auth()->user()->role === 'provider' ? __('Usta xonasi') : __('Usta bo\'lish') }}
                </h3>
                <p class="text-xs text-gray-500 mt-1">
                    {{ auth()->user()->role === 'provider' ? __('Mijozlardan kelgan buyurtmalarni ko\'rish') : __('Platformada usta sifatida ro\'yxatdan o\'tish') }}
                </p>
            </a>

            @if (auth()->user()->role === 'admin')
                <a href="{{ route('admin.dashboard') }}"
                    class="group p-5 rounded-2xl border border-gray-200/70 dark:border-gray-800 bg-white dark:bg-[#111110] hover:border-accent hover:shadow-[0_0_25px_rgba(14,165,233,0.15)] transition-all">
                    <div class="w-10 h-10 rounded-xl bg-amber-500/10 text-amber-500 dark:text-amber-400 flex items-center justify-center font-bold text-base mb-3 group-hover:scale-105 transition-transform">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white group-hover:text-accent transition-colors">
                        {{ __('Admin Boshqaruv') }}
                    </h3>
                    <p class="text-xs text-gray-500 mt-1">
                        {{ __('Platformani to\'liq boshqarish va monitoring') }}
                    </p>
                </a>
            @else
                <a href="{{ route('profile.edit') }}"
                    class="group p-5 rounded-2xl border border-gray-200/70 dark:border-gray-800 bg-white dark:bg-[#111110] hover:border-accent hover:shadow-[0_0_25px_rgba(14,165,233,0.15)] transition-all">
                    <div class="w-10 h-10 rounded-xl bg-gray-500/10 text-gray-500 dark:text-gray-400 flex items-center justify-center font-bold text-base mb-3 group-hover:scale-105 transition-transform">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white group-hover:text-accent transition-colors">
                        {{ __('Profil sozlamalari') }}
                    </h3>
                    <p class="text-xs text-gray-500 mt-1">
                        {{ __('Shaxsiy ma\'lumotlar va parolni o\'zgartirish') }}
                    </p>
                </a>
            @endif
        </div>

    </div>
</x-app-layout>
