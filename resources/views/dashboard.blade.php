<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("You're logged in!") }}

                    <div class="mt-3 small text-muted">
                        <strong>Debug:</strong>
                        <div>Session ID: {{ session()->getId() }}</div>
                        <div>Auth ID: {{ auth()->id() ?? 'guest' }}</div>
                        <div>Auth Email: {{ auth()->user()->email ?? 'n/a' }}</div>
                        <div>Role: {{ auth()->user()->role ?? 'n/a' }}</div>
                        <div>APP_URL: {{ config('app.url') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
