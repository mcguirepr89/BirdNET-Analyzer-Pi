<div wire:poll.visible class="py-6 bg-white dark:bg-slate-700">
    @if (file_exists('storage/Segments/currently_analyzing.png'))
    <div class="text-center font-semibold pb-4 dark:text-gray-200">Currently Analyzing</div>
    <div class="md:px-6 py-4">
    <img class="mx-auto p-3 bg-black md:rounded-lg shadow-lg" 
         src="storage/Segments/currently_analyzing.png?nocache=<?= time();?>">
    </div>
    @endif
</div>