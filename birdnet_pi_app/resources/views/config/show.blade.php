<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Current BirdNET Configuration') }}
        </h2>
    </x-slot>

    <div class="p-3">
    	<div class="bg-white overflow-hidden shadow-xl sm:rounded-xl h-full">
    	   <x-config-view />
    	</div>
    </div>
</x-app-layout>