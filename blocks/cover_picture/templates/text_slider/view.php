<?php
defined('C5_EXECUTE') or die('Access Denied.');

if ($fileObject === null) {
    echo '<div style="color: red; font-weight: bold;">' . t('No picture selected') . '</div>';
    return;
}
?>
<div id="cover-picture-<?php echo $bID ?>" class="cover-picture" style="width: <?php echo $fileWidth; ?>px; height: <?php echo $fileHeight; ?>px; overflow: hidden;">
    <div class="cover-picture-picture">
        <img src="<?php echo $fileURL; ?>" alt=""/>
    </div>
    <div class="cover-picture-text" style="left: <?php echo $fileWidth; ?>px">
        <?php echo $content ?>
    </div>    
</div>