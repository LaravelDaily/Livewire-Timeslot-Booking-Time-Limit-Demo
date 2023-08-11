<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Appointment Confirmed') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="font-sans text-gray-900 antialiased">
                <div class="w-full sm:max-w-5xl mt-6 mb-6 px-6 py-8 bg-white shadow-md overflow-hidden sm:rounded-lg">
                    <p class="text-green-600">Appointment confirmed at {{ $appointment->start_time }}</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
