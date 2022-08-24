<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('!! Manually Editing BirdNET Configuration !!') }}
        </h2>
    </x-slot>

    <div class="p-3 h-full">
    	<div class="bg-white overflow-hidden shadow-xl rounded-xl">
    	   <x-config-editor />
    	</div>
    </div>
</x-app-layout>
