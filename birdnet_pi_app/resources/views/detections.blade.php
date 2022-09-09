<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-400 leading-tight">
            {{ __('Detections') }}
        </h2>
    </x-slot>

    <div class="md:py-12">
        <div class="w-full sm:max-w-7xl mx-auto md:px-6 lg:px-8">
            <div class="overflow-hidden shadow-xl sm:rounded-lg">
                @if (!isset($_GET['page']) && !isset($_GET['search']) && !isset($_GET['file_name']))
                    <livewire:currently-analyzing />
                @endif
                @if(\App\Models\Detection::count())
                    <livewire:detections-table />
                @else
                    <h2 class="text-center text-2xl p-8">No detections yet</h2>
                @endif
        </div>
    </div>
</x-app-layout>