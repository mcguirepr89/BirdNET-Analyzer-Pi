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
                    @if($api_method !== 'HEAD')
                        <div class="ml-3">
                        <a href="/{{$api_route->uri }}">{{$api_route->uri}}</a> {{ $api_method }}
                        </div>
                        <br>
                    @endif
                @endforeach
            @endforeach
        </div>
    </div>
</x-app-layout>
