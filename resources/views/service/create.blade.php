<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 p-10"">
        <form method="POST" action="{{ route('service-requests.store') }}">
            @csrf
            <div>
                <x-input-label value="Xizmat turi" />
                <select name="category_id" required class="w-full mt-1 border rounded dark:text-white dark:bg-gray-900">
                    <option value="">Tanlang</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('category_id')" />
            </div>

            {{-- Phone --}}
            <div class="mt-4">
                <x-input-label value="Telefon" />
                <x-text-input name="phone" class="w-full" required />
                <x-input-error :messages="$errors->get('phone')" />
            </div>

            {{-- City --}}
            <div class="mt-4">
                <x-input-label value="Shahar" />
                <x-text-input name="city" class="w-full" required />
                <x-input-error :messages="$errors->get('city')" />
            </div>

            <div class="mt-6">
                <x-primary-button>Yuborish</x-primary-button>
            </div>
        </form>
    </div>
</x-app-layout>