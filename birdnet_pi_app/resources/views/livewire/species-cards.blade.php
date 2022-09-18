<div wire:poll.10s.visible>
    <div class="text-center pb-6 font-semibold text-gray-900 dark:text-gray-200">
        Total Detections = <span class="text-xl"><?=number_format($total_detections);?></span>
    </div>

    <div class="flex flex-col bg-white dark:bg-slate-800 rounded-lg drop-shadow-xl">
        {{-- <div class="max-w-7xl"> --}}
            @foreach ($species_stats as $species)
            <div class="py-2 mb-5 bg-white dark:bg-slate-600 shadow rounded-lg">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 p-3 xl:px-10 gap-y-8 gap-x-12 2xl:gap-x-28">
                    <div class="w-full">
                        <p tabindex="0" class="focus:outline-none text-xs md:text-sm font-medium leading-none text-gray-500 dark:text-gray-300 uppercase">
                            <a href="https://<?php if(isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])){echo substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2).".";}?>wikipedia.org/wiki/{{$species->sci_name}}" class="hover:text-green-300 hover:bg-blue-800 hover:rounded-2xl hover:overflow-hidden hover:z-0 italic" target="_blank">{{ $species->sci_name}}</a>
                        </p>
                        <p class="focus:outline-none text-lg sm:text-xl md:text-2xl lg:text-3xl font-bold leading-3 text-gray-800 dark:text-gray-300 mt-3 md:mt-5"><a tabindex="0" href="{{ route('species').'/'.$species->com_name }}" class="hover:text-green-400">{{ $species->com_name }}</a></p>
                    </div>
                    <div class="w-full">
                        <p tabindex="0" class="focus:outline-none text-xs md:text-sm font-medium leading-none text-gray-500 dark:text-gray-300 uppercase">Total</p>
                        <p tabindex="0" class="focus:outline-none text-lg sm:text-xl md:text-2xl lg:text-3xl font-bold leading-3 text-gray-800 dark:text-gray-300 mt-3 md:mt-5"><?=number_format($species->species_total);?></p>
                    </div>
                    <div class="w-full">
                        <p tabindex="0" class="focus:outline-none text-xs md:text-sm font-medium leading-none text-gray-500 dark:text-gray-300 uppercase">Highest confidence score: </p>
                        <p tabindex="0" class="focus:outline-none text-lg sm:text-xl md:text-2xl lg:text-3xl font-bold leading-3 text-gray-800 dark:text-gray-300 mt-3 md:mt-5">
                            {{ $species->confidence }}
                            @php
                            $spectrogram = str_replace(".wav", ".png", $species->file_name);
                            @endphp
                            @if (file_exists($spectrogram))
                            <video class="bg-black p-3 mb-9 max-w-full mx-auto md:rounded-lg shadow-2xl" id="spectrogram-video"  poster="<?= $spectrogram;?>" onmouseover="this.setAttribute('controls','controls')" onmouseout="this.removeAttribute('controls')">
                              <source src="{{ $species->file_name }}" type="audio/mpeg">
                            </video>
                            @else
                            <audio controls class="mx-auto w-32 md:w-64">
                                <source src="{{ $species->file_name }}" type="audio/mpeg">Listen
                            </audio>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
            @endforeach
        {{-- </div> --}}
    </div>
</div>
