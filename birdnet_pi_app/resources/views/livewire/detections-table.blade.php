@php
    use Illuminate\Http\Request;
@endphp
@if (isset($_GET['page']) || isset($_GET['search']) || isset($_GET['file_name']))
<div class="flex flex-col bg-white dark:bg-slate-700">
@else
<div wire:poll.10s.visible  class="flex flex-col bg-white dark:bg-slate-700">
@endif
    <div class="overflow-x-auto sm:-mx-6 lg:-mx-8 ">
      <div class="py-2 inline-block min-w-full sm:px-6 lg:px-8">
        <div class="overflow-hidden">
          <table class="min-w-full text-center">
            <thead class="">
              <tr>
                <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4">
                  <?php if(!isset($_GET['file_name'])){?>
                  <form name="search" method="GET" action="">
                    <input name="search" id="search" class="text-center text-gray-600 dark:text-gray-200  focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-700 bg-white dark:bg-slate-400 font-normal w-64 h-10 text-sm border-gray-300 dark:border-slate-900 rounded-full border shadow-lg" placeholder="{{ request('search', 'Search by species') }}" type="search"/>
                  </form>
                  <?php }?>
                </th>
              </tr>
            </thead>
            <tbody>
              @foreach ($detections as $detection)
              <tr class="">
                <td class="p-0 text-md text-gray-900 dark:text-gray-200 font-medium md:px-6 py-4">
                  <a href="https://<?php if(isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])){echo substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2).".";}?>wikipedia.org/wiki/{{$detection->sci_name}}" class="p-2 hover:text-green-300 hover:bg-blue-800 hover:rounded-2xl hover:overflow-hidden hover:z-0" target="_blank">{{ $detection->com_name }}</a>
                  {{ $detection->confidence }} --
                  <?php if(!isset($_GET['file_name']))
                  {
                    echo $detection->created_at->diffForHumans();?>
                    -- <a class="hover:font-semibold hover:text-green-600" href="/detections?file_name=<?= $detection->file_name;?>">Detection Link</a>
                  <?php }else{
                    echo $detection->created_at;
                  };?> 
                  @php
                  $spectrogram = str_replace(".wav", ".png", $detection->file_name);
                  @endphp
                  @if (file_exists($spectrogram))
                      <video class="bg-black p-3 mb-9 max-w-full mx-auto md:rounded-lg shadow-2xl" id="spectrogram-video"  poster="<?= $spectrogram;?>" onmouseover="this.setAttribute('controls','controls')" onmouseout="this.removeAttribute('controls')">
                        <source src="{{ $detection->file_name }}" type="audio/mpeg">
                      </video>
                  @else
                    <audio controls class="mx-auto w-32 md:w-64">
                      <source src="{{ $detection->file_name }}" type="audio/mpeg">Listen
                    </audio>
                  @endif
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <div class="p-6">
      {{ $detections->links() }}
    </div>
  </div>
