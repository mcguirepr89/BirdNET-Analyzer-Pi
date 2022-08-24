<textarea id="texteditor"><?php 

$fo = fopen('assets/birdnet_config.php', 'r'); 
$pageText = fread($fo, filesize('assets/birdnet_config.php')); 
echo nl2br($pageText);

?></textarea>
<script>
tinymce.init({
    selector: '#texteditor',
    branding: false,
    elementpath: false,
    height: 600
});
</script>
