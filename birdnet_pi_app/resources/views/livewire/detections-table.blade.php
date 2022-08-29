@php
    use Illuminate\Http\Request;
@endphp
<div class="flex flex-col">
    <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
      <div class="py-2 inline-block min-w-full sm:px-6 lg:px-8">
        <div class="overflow-hidden">
          <table class="min-w-full text-center">
            <thead class="">
              <tr>
                <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4">
                  <form name="search" method="GET" action="">
                    <input name="search" id="search" class="text-center text-gray-600  focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-700 bg-white font-normal w-64 h-10 text-sm border-gray-300 rounded border shadow" placeholder="{{ request('search', 'Search by species') }}" type="search"/>
                  </form>
                </th>
              </tr>
            </thead>
            <tbody>
              @foreach ($detections as $detection)
              <tr class="">
                <td class="text-md text-gray-900 font-medium px-6 py-4 whitespace-nowrap">
                  <a href="https://wikipedia.org/wiki/{{$detection->sci_name}}" class="p-2 hover:text-green-300 hover:bg-blue-800 hover:rounded-2xl hover:overflow-hidden hover:z-0" target="_blank">{{ $detection->com_name }}</a>
                  {{ $detection->confidence }} --
                  {{ $detection->created_at->diffForHumans() }}
                  @php
                  $spectrogram = str_replace(".wav", ".png", $detection->file_name);
                  @endphp
                  @if (file_exists($spectrogram))
                      <video class="z-20 bg-black p-3 max-w-full mx-auto rounded shadow-lg" id="spectrogram-video"  poster="<?= $spectrogram;?>" onmouseover="this.setAttribute('controls','controls')" onmouseout="this.removeAttribute('controls')">
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
