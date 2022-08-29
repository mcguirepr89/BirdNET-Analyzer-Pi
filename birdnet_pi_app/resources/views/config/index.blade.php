<x-app-layout>
    {{-- <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Current BirdNET Configuration') }}
        </h2>
    </x-slot> --}}

    <div class="sm:p-3">
    	<div class="max-w-7xl mx-auto bg-white shadow-xl sm:rounded-xl">
            <x-config-editor />
    	</div>
    </div>
</x-app-layout>