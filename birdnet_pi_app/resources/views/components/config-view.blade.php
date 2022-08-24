<div class="py-2 px-4 max-w-2xl">
    @php
        $fo = fopen('assets/birdnet_config.php', 'r'); 
        $pageText = fread($fo, filesize('assets/birdnet_config.php')); 
        echo nl2br($pageText);
    @endphp
</div>