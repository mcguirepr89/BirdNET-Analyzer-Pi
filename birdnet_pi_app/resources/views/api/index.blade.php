<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-400 leading-tight">
            {{ __('API Tokens') }}
        </h2>
    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            @livewire('api.api-token-manager')

            {{-- Show Available API Endpoints --}}
            @php
            $routes = Route::getRoutes()->get();
            foreach ($routes as $route)
            {
                if(str_starts_with($route->uri, 'api'))
                {
                    $api_routes[] = $route;
                }
            }
            @endphp
            <div class="dark:text-gray-200 text-center max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                <div class="text-2xl text-center p-3">Available API Endpoints</div>
                @foreach ($api_routes as $api_route)
                    @foreach ($api_route->methods as $api_method)
                        @if($api_method !== 'HEAD')
                        <div class="p-3 ml-3">
                            <a href="/{{$api_route->uri }}">{{$api_route->uri}}</a> {{ $api_method }}<br>
                        </div>
                        @endif
                    @endforeach
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
