<section>
    <header>
        <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 flex items-center gap-2">
            <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            {{ __('Profil ma\'lumotlari') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Profil ma'lumotlaringizni o'zgartirish va profil rasmini yangilash.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <!-- Avatar Upload Section -->
        <div x-data="{
            photoName: null,
            photoPreview: null,
            removePhoto: false
        }" class="p-4 rounded-xl border border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-[#161615]">
            <x-input-label :value="__('Profil rasmi')" class="mb-3" />

            <input type="file" id="avatar" name="avatar" class="hidden" accept="image/png, image/jpeg, image/jpg, image/webp, image/gif"
                   x-ref="photo"
                   x-on:change="
                        if ($refs.photo.files[0]) {
                            photoName = $refs.photo.files[0].name;
                            const reader = new FileReader();
                            reader.onload = (e) => {
                                photoPreview = e.target.result;
                                removePhoto = false;
                            };
                            reader.readAsDataURL($refs.photo.files[0]);
                        }
                   " />

            <input type="hidden" name="remove_avatar" :value="removePhoto ? '1' : '0'">

            <div class="flex flex-col sm:flex-row sm:items-center gap-5">
                <!-- Avatar Preview -->
                <div class="relative">
                    <template x-if="photoPreview">
                        <img :src="photoPreview" alt="{{ $user->name }}" class="w-20 h-20 rounded-full object-cover ring-2 ring-accent shadow-md">
                    </template>
                    <template x-if="!photoPreview && !removePhoto">
                        @if ($user->avatar_url)
                            <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-20 h-20 rounded-full object-cover ring-2 ring-gray-200 dark:ring-gray-700 shadow-md">
                        @else
                            <div class="w-20 h-20 rounded-full bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 text-white text-2xl font-black flex items-center justify-center shadow-md select-none ring-2 ring-gray-200 dark:ring-gray-700">
                                {{ $user->initials }}
                            </div>
                        @endif
                    </template>
                    <template x-if="!photoPreview && removePhoto">
                        <div class="w-20 h-20 rounded-full bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 text-white text-2xl font-black flex items-center justify-center shadow-md select-none ring-2 ring-gray-200 dark:ring-gray-700">
                            {{ $user->initials }}
                        </div>
                    </template>
                </div>

                <!-- Avatar Actions -->
                <div class="flex flex-col gap-2">
                    <div class="flex flex-wrap items-center gap-2">
                        <button type="button" x-on:click.prevent="$refs.photo.click()" class="inline-flex items-center gap-2 px-3.5 py-2 text-xs font-semibold rounded-lg bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-200 border border-gray-300 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-750 transition shadow-sm cursor-pointer">
                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span>{{ __('Rasm yuklash') }}</span>
                        </button>

                        @if ($user->avatar)
                            <button type="button" x-show="!removePhoto" x-on:click="removePhoto = true; photoPreview = null; if ($refs.photo) $refs.photo.value = ''" class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-medium rounded-lg text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/40 border border-transparent hover:border-rose-200 dark:hover:border-rose-800 transition cursor-pointer">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                <span>{{ __('Rasmni o\'chirish') }}</span>
                            </button>
                        @endif

                        <button type="button" x-show="photoPreview" x-on:click="photoPreview = null; if ($refs.photo) $refs.photo.value = ''" class="inline-flex items-center gap-1 px-3 py-2 text-xs text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 cursor-pointer">
                            {{ __('Bekor qilish') }}
                        </button>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        {{ __('JPG, PNG, WEBP yoki GIF (maksimal 5MB). Agar rasm yuklanmasa, ismingizning bosh harfi ko\'rsatiladi.') }}
                    </p>
                </div>
            </div>
            <x-input-error class="mt-2" :messages="$errors->get('avatar')" />
        </div>

        <div>
            <x-input-label for="name" :value="__('Ism')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-3 p-3 rounded-lg bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-800">
                    <p class="text-sm text-amber-800 dark:text-amber-200 flex items-center justify-between gap-2 flex-wrap">
                        <span>{{ __('Email manzilingiz hali tasdiqlanmagan.') }}</span>

                        <button form="send-verification" class="text-xs font-semibold underline text-amber-900 dark:text-amber-100 hover:text-black dark:hover:text-white rounded-md focus:outline-none cursor-pointer">
                            {{ __('Tasdiqlash xatini qayta yuborish') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-xs text-green-600 dark:text-green-400">
                            {{ __('Email manzilingizga yangi tasdiqlash havolasi yuborildi.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4 pt-2">
            <x-primary-button>{{ __('Saqlash') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2500)"
                    class="text-sm text-emerald-600 dark:text-emerald-400 font-medium flex items-center gap-1.5"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    {{ __('Muvaffaqiyatli saqlandi.') }}
                </p>
            @endif
        </div>
    </form>
</section>
