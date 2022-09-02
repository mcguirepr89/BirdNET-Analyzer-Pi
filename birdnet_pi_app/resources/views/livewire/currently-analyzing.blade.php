<div wire:poll.visible class="py-6">
    @if (file_exists('storage/Segments/currently_analyzing.png'))
    <div class="text-center font-semibold pb-4">Currently Analyzing</div>
    <img class="mx-auto p-3 bg-black rounded-lg shadow-lg" 
         src="storage/Segments/currently_analyzing.png?nocache=<?= time();?>">
    @endif
</div>