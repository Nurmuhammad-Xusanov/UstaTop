<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">
            Provider Requests
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto">
        <div class="bg-white dark:bg-gray-800 rounded shadow p-6">

            <table class="w-full text-left">
                <tbody>
                    @foreach($requests as $r)
                        <tr class="border-b">
                            <td>{{ $r->user->name ?? "Unknown" }}</td>
                            <td>{{ $r->city }}</td>
                            <td>{{ $r->status }}</td>
                            <td>
                                <a href="{{ route('provider-requests.show', $r) }}"
                                   class="text-blue-500">
                                    View
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>
</x-app-layout>
