<div class="md:col-span-1 flex justify-center">
    <div class="px-6 sm:px-0 ">
        <h3 class="text-center text-lg font-medium text-gray-900 sm:sticky sm:top-0">
            {{ $title }}
        </h3>
      
        <p class="text-center mt-1 text-sm text-gray-600 sm:sticky sm:top-6">
            {{ $description }}
        </p>
    </div>

    <div class="px-4 sm:px-0">
        {{ $aside ?? '' }}
    </div>
</div>
