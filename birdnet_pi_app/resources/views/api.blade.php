<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-400 leading-tight">
            {{ __('API Endpoints') }}
        </h2>
    </x-slot>

    <div class="md:py-12">
        <div class="dark:text-gray-200 w-full sm:max-w-7xl mx-auto md:px-6 lg:px-8">
            @foreach ($api_routes as $api_route)
                @foreach ($api_route->methods as $api_method)
                    {{$api_route->uri }} {{ $api_method }}<br>
                @endforeach
            @endforeach
        </div>
    </div>
</x-app-layout>
