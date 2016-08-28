<?php
defined('C5_EXECUTE') or die('Access Denied.');

if ($fileObject === null) {
    echo '<div style="color: red; font-weight: bold;">' . t('No picture selected') . '</div>';
    return;
}
?>
<div id="cover-picture-<?php echo $bID; ?>" class="cover-picture cover-picture-text-centered" style="width: <?php echo $fileWidth; ?>px; height: <?php echo $fileHeight; ?>px; overflow: hidden;">
    <img src="<?php echo $fileURL; ?>" alt=""/>
    <div class="cover-picture-text">
        <div class="cover-picture-overlay"></div>
        <div class="cover-picture-text-inner">
            <?php echo $content; ?>
        </div>    
    </div>    
</div>