<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
        {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
            {{ __('A new verification link has been sent to the email address you provided during registration.') }}
        </div>
    @endif

    @if (session('status') == 'verification-link-failed')
        <div class="mb-4 font-medium text-sm text-rose-600 dark:text-rose-400">
            {{ __('Email xizmati bilan vaqtincha bog\'lanib bo\'lmadi. Iltimos, birozdan so\'ng qayta urinib ko\'ring.') }}
        </div>
    @endif

    <div class="mt-4 flex items-center justify-between">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <div>
                <x-primary-button>
                    {{ __('Resend Verification Email') }}
                </x-primary-button>
            </div>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                {{ __('Log Out') }}
            </button>
        </form>
    </div>

    <script>
        // Automatically redirect to dashboard as soon as email is verified on phone or another browser
        (function() {
            var checkInterval = setInterval(function () {
                fetch('{{ route("verification.status") }}', {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(function (res) {
                    if (res.ok) {
                        return res.json();
                    }
                    return null;
                })
                .then(function (data) {
                    if (data && data.verified) {
                        clearInterval(checkInterval);
                        window.location.href = '{{ route("dashboard") }}';
                    }
                })
                .catch(function () {});
            }, 2000);
        })();
    </script>
</x-guest-layout>
