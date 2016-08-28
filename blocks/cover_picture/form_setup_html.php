<?php
use Concrete\Core\Support\Facade\Facade;

defined('C5_EXECUTE') or die('Access Denied.');

/* @var Concrete\Core\Block\View\BlockView $this */
/* @var Concrete\Package\CoverPicture7\Block\CoverPicture\Controller $controller */

$app = Facade::getFacadeApplication();
$fch = $app->make('helper/form/color');
/* @var Concrete\Core\Form\Service\Widget\Color $fch */
$al = $app->make('helper/concrete/asset_library');
/* @var Concrete\Core\Application\Service\FileManager $al */
?>

<fieldset>
    <legend><?php echo t('Cover Picture')?></legend>
    <div class="form-group">
    	<?php
    	echo $al->image('fID', 'fID', t('Picture'), $fID ? File::getByID($fID) : null);
    	?>
    </div>
</fieldset>

<fieldset>
    <legend><?php echo t('Text')?></legend>
	<div class="form-group">
		<?php
		$editor = Core::make('editor');
		/* @var Concrete\Core\Editor\RedactorEditor $editor */
		$pm = $editor->getPluginManager();
		if ($pm->isAvailable('fontcolor')) {
		    $pm->select('fontcolor');
		}
		echo $editor->outputBlockEditModeEditor('content', $controller->getContentEditMode());
		?>
	</div>
</fieldset>

<fieldset>
    <legend><?php echo t('Overlay Color')?></legend>
	<div class="form-group">
		<?php
		  echo $fch->output('overlayColor', $overlayColor);
		?>
	</div>
</fieldset>

<fieldset>
    <legend><?php echo t('Overlay Picture')?></legend>
    <div class="form-group">
    	<?php
    	echo $al->image('overlayfID', 'overlayfID', t('Picture'), $overlayfID ? File::getByID($overlayfID) : $overlayfID);
    	?>
    </div>
</fieldset>

<fieldset>
    <legend><?php echo t('Overlay Opacity') ?></legend>
    <div style="position: relative; height: 30px;">
        <div style="position: absolute; top: 0; left: 0; right: 40px;">
            <div id="overlayOpacity"></div>
        </div>
        <div style="position: absolute; top: 0; width: 40px; right: 0px; text-align: right;">
            <input type="text" style="width: 30px;" name="overlayOpacityValue" id="overlayOpacityValue" value="<?php echo h($overlayOpacity); ?>"/>
        </div>
    </div>
</fieldset>

<script>
    $(function () {
        var slider = $("#overlayOpacity").slider({
            min: 0.0,
            max: 1.0,
            step: 0.1,
            value: <?php echo $overlayOpacity; ?>,
            slide: function (event, ui) {
                $("#overlayOpacityValue").val(ui.value);
            }
        });
        $("#overlayOpacityValue").change(function () {
            slider.slider("value", $(this).val());
        });
    });
</script>