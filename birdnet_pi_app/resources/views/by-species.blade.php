<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-400 leading-tight">
            {{ __('') }}
        </h2>
    </x-slot>

    <div class="md:py-12">
        <div class="w-full sm:max-w-7xl mx-auto md:px-6 lg:px-8">
            <div class="overflow-hidden sm:rounded-lg">
                {{-- <div class="py-2 mb-5 bg-white dark:bg-slate-600 shadow rounded-lg">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 p-3 xl:px-10 gap-y-8 gap-x-12 2xl:gap-x-28">
                        <div class="w-full">
                            <p tabindex="0" class="focus:outline-none text-xs md:text-sm font-medium leading-none text-gray-500 dark:text-gray-300 uppercase">
                                <a href="https://<?php if(isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])){echo substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2).".";}?>wikipedia.org/wiki/{{$species[0]['sci_name']}}" class="hover:text-green-300 hover:bg-blue-800 hover:rounded-2xl hover:overflow-hidden hover:z-0" target="_blank">{{ $species[0]['sci_name'] }}</a>
                            </p>
                            <p tabindex="0" class="focus:outline-none text-lg sm:text-xl md:text-2xl lg:text-3xl font-bold leading-3 text-gray-800 dark:text-gray-300 mt-3 md:mt-5">{{ $species[0]['com_name'] }}</p>
                        </div>
                    </div>
                </div> --}}
                @if (isset($_GET['sortbyconf']))
                    @foreach ($species_byconf  as $detection)
                    <a href="{{ url($detection->file_name) }}" class="group text-slate-900 dark:text-gray-200 z-10"><?= basename($detection->file_name);?><br>
                        <div class="invisible h-0 w-0 group-hover:visible group-hover:h-fit group-hover:w-1/2 group-hover:ease-in group-hover:duration-200 absolute top-20 right-5 z-0"><img src="{{ url(str_replace('.wav', '.png', $detection->file_name)) }}"></div></a>
                    @endforeach
                @else
                    @foreach ($species_bytime as $detection)
                        <a href="{{ url($detection->file_name) }}" class="group text-slate-900 dark:text-gray-200 z-10"><?= basename($detection->file_name);?><br>
                        <div class="invisible h-0 w-0 group-hover:visible group-hover:h-fit group-hover:w-1/2 group-hover:ease-in group-hover:duration-200 absolute top-20 right-5 z-0"><img src="{{ url(str_replace('.wav', '.png', $detection->file_name)) }}"></div></a>
                    @endforeach  
                @endif

            </div>
            @if (isset($_GET['sortbyconf']))
                <div class="p-6">
                   {{ $species_byconf->links() }}
                </div>
            @else
                <div class="p-6">
                    {{ $species_byconf->links() }}
                </div>
            @endif
        </div>  
    </div>
</x-app-layout>