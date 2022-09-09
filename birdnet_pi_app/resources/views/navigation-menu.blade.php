@if(Auth::check())
<nav x-data="{ open: false }" class="bg-white dark:bg-slate-900 border-b border-gray-100 dark:border-none">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="/">
                        <x-jet-application-mark class="block h-9 w-auto" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <x-jet-nav-link href="{{ route('detections.index') }}" :active="request()->routeIs('detections.index')">
                        {{ __('Detections') }}
                    </x-jet-nav-link>
                    <x-jet-nav-link href="{{ route('species') }}">
                        {{ __('Species') }}
                    </x-jet-nav-link>
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ml-6">
                <!-- Settings Dropdown -->
                <div class="ml-3 relative">
                    <x-jet-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                <button class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                                    <img class="h-8 w-8 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                                </button>
                            @else
                                <span class="inline-flex rounded-md">
                                    <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition">
                                        {{ Auth::user()->name }}

                                        <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </span>
                            @endif
                        </x-slot>

                        <x-slot name="content">
                            <!-- Content -->
                            <div class="block px-4 py-2 text-xs text-gray-400 dark:text-gray-200">
                                {{ __('Content') }}
                            </div>
                            <x-jet-dropdown-link href="{{ route('detections.index')}}">
                                {{ __('Detections') }}
                            </x-jet-dropdown-link>
                            <x-jet-dropdown-link href="{{ route('species')}}">
                                {{ __('Species') }}
                            </x-jet-dropdown-link>

                            <!-- Tools -->
                            <div class="block px-4 py-2 text-xs text-gray-400 dark:text-gray-200">
                                {{ __('Tools') }}
                            </div>

                            <x-jet-dropdown-link href="/logs">
                                {{ __('Live Log') }}
                            </x-jet-dropdown-link>

                            @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                                <x-jet-dropdown-link href="{{ route('api-tokens.index') }}">
                                    {{ __('API Tokens') }}
                                </x-jet-dropdown-link>
                            @endif

                            <!-- Settings -->
                            <div class="block px-4 py-2 text-xs text-gray-400 dark:text-gray-200">
                                {{ __('Settings') }}
                            </div>

                            <x-jet-dropdown-link href="{{ route('profile.show') }}">
                                {{ __('Profile') }}
                            </x-jet-dropdown-link>


                            <x-jet-dropdown-link href="{{ route('config.index') }}">
                                {{ __('Configuration') }}
                            </x-jet-dropdown-link>

                            {{-- <div class="border-t border-gray-100"></div> --}}

                            <div class="grid grid-cols-2">
                                <div>
                                <!-- Authentication -->
                                <form method="POST" action="{{ route('logout') }}" x-data>
                                    @csrf

                                    <x-jet-dropdown-link href="{{ route('logout') }}"
                                             @click.prevent="$root.submit();">
                                        {{ __('Log Out') }}
                                    </x-jet-dropdown-link>
                                </form></div>
                                <div class="text-right">
                                    <!-- Dark Mode Switcher -->
                                    <x-dark-mode-switcher />
                                </div>
                            </div>
                      
                        </x-slot>
                    </x-jet-dropdown>
                </div>
            </div>

            <!-- Hamburger -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-green-400 focus:outline-none focus:bg-green-400 focus:text-gray-500 transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <!-- Content -->
        <div class="block px-4 py-2 text-xs text-gray-400 dark:text-gray-200">
            {{ __('Content') }}
        </div>
        <div class="pt-2 pb-3 space-y-1">
            <x-jet-responsive-nav-link href="{{ route('detections.index') }}" :active="request()->routeIs('detections.index')">
                {{ __('Detections') }}
            </x-jet-responsive-nav-link>
            <x-jet-responsive-nav-link href="{{ route('species') }}" :active="request()->routeIs('species')">
                {{ __('Species') }}
            </x-jet-responsive-nav-link>
        </div>

        <!-- Tools -->
        <div class="block px-4 py-2 text-xs text-gray-400 dark:text-gray-200">
            {{ __('Tools') }}
        </div>
        <div class="pt-2 pb-3 space-y-1">
            <x-jet-responsive-nav-link href="/logs">
                {{ __('Live Log') }}
            </x-jet-responsive-nav-link>
        </div>
        <x-jet-responsive-nav-link href="{{ route('api-tokens.index') }}" :active="request()->routeIs('api-tokens.index')">
            {{ __('API Tokens') }}
        </x-jet-responsive-nav-link>

        <!-- Responsive Settings Options -->
        {{-- <div class="pt-4 pb-1 border-t border-gray-200"> --}}
            {{-- <div class="flex items-center justify-center px-4">
                @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                    <div class="shrink-0 mr-4">
                        <img class="h-10 w-10 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                    </div>
                @endif
            </div> --}}


        <!-- Settings -->
        <div class="block px-4 py-2 text-xs text-gray-400 dark:text-gray-200">
            {{ __('Settings') }}
        </div>
            <div class="mt-3 space-y-1">
                <!-- Account Management -->
                <x-jet-responsive-nav-link href="{{ route('profile.show') }}" :active="request()->routeIs('profile.show')">
                    {{ __('User Settings') }}
                </x-jet-responsive-nav-link>

                <x-jet-responsive-nav-link href="{{ route('config.index') }}" :active="request()->routeIs('config.index')">
                    {{ __('Configuration') }}
                </x-jet-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}" x-data>
                    @csrf

                    <x-jet-responsive-nav-link href="{{ route('logout') }}"
                                   @click.prevent="$root.submit();">
                        {{ __('Log Out') }}
                    </x-jet-responsive-nav-link>
                </form>
            </div>
        {{-- </div> --}}
    </div>
</nav>
@else
<nav x-data="{ open: false }" class="bg-white dark:bg-slate-900">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('login') }}">
                        <x-jet-application-mark class="block h-9 w-auto" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <x-jet-nav-link href="{{ route('detections.index') }}" :active="request()->routeIs('detections.index')">
                        {{ __('Detections') }}
                    </x-jet-nav-link>
                    <x-jet-nav-link href="{{ route('species') }}">
                        {{ __('Species') }}
                    </x-jet-nav-link>
                </div>
            </div>
        </div>
    </div>
</nav>
@endif
