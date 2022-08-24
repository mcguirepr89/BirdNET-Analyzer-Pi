<div class="flex flex-col">
    <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
      <div class="py-2 inline-block min-w-full sm:px-6 lg:px-8">
        <div class="overflow-hidden">
          <table class="min-w-full text-center">
            <thead class="border-b">
              <tr>
                <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4">
                  Species
                </th>
                <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4">
                  Confidence
                </th>
                <th scope="col" class="hidden sm:block text-sm font-medium text-gray-900 px-6 py-4">
                  Time
                </th>
                <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4">
                  Recording
                </th>
              </tr>
            </thead>
            <tbody>
              @foreach ($detections as $detection)
              <tr class="border-b">
                <td class="text-sm text-gray-900 font-medium px-6 py-4 whitespace-nowrap hover:text-green-400">
                  <a href="https://wikipedia.org/wiki/{{$detection->sci_name}}" target="_blank">{{ $detection->com_name }}</a>
                </td>
                <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                  {{ $detection->confidence }}
                </td>
                <td class="hidden sm:block text-sm text-gray-900 font-light px-6 py-8 whitespace-nowrap">
                  {{ $detection->created_at->diffForHumans() }}
                </td>
                <td class="text-sm text-gray-900 font-light p-4 whitespace-nowrap">
                  <audio controls class="mx-auto w-32 md:w-64 lg:w-full ">
                    <source src="{{ $detection->file_name }}" type="audio/mpeg">Listen
                  </audio>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>