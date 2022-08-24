<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('BirdNET Configuration Form') }}
        </h2>
    </x-slot>

    <div class="p-3 h-full">
    	<div class="bg-white shadow-xl rounded-xl max-w-7xl mx-auto">
    	   <x-config-form :settings="$settings" />
    	</div>
    </div>
</x-app-layout>