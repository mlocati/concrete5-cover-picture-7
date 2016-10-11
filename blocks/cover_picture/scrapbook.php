<?php
if ($fileObject === null) {
    echo '<div style="color: red; font-weight: bold;">' . t('No picture selected') . '</div>';
} else {
    ?><div style="text-align: center"><img src="<?php echo $fileURL; ?>" style="width: <?php echo $fileWidth; ?>px; max-width: 100%" /></div><?php
}
