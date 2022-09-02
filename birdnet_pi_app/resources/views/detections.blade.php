<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detections') }}
        </h2>
    </x-slot>

    <div class="md:py-12">
        <div class="w-full sm:max-w-7xl mx-auto md:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <livewire:currently-analyzing />
                @if(\App\Models\Detection::count())
                <livewire:detections-table />

                    
                @else
                <h2 class="text-center text-2xl p-8">No detections yet</h2>
                @endif
        </div>
    </div>
</x-app-layout>