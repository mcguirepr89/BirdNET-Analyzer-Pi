<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-400 leading-tight">
            {{ __('Species') }}
        </h2>
    </x-slot>

    <div class="md:py-12">
        <div class="w-full sm:max-w-7xl mx-auto md:px-6 lg:px-8">
            <div class="overflow-hidden sm:rounded-lg">
                <livewire:species-cards />
            </div>
        </div>  
    </div>
</x-app-layout>