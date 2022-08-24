<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detections') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                @if($detections->isEmpty())
                    <h2 class="text-center text-2xl p-8">No detections yet</h2>
                @else
                    <livewire:detections-table />
                    <div class="p-6">
                      {{ $detections->links() }}
                    </div>
                @endif
        </div>
    </div>
</x-app-layout>