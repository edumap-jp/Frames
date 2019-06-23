<?php
/**
 * Element of frame.
 *  - $frame: The frame data
 *  - $view: The plugin view
 *
 * @copyright Copyright 2014, NetCommons Project
 * @author Kohei Teraguchi <kteraguchi@commonsnet.org>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 */

if ($frame['header_type'] === 'none' && ! Current::isSettingMode()) {
	$panelCss = ' panel-none';
} elseif (!empty($frame['header_type'])) {
	$panelCss = ' panel panel-' . h($frame['header_type']);
} else {
	$panelCss = ' panel panel-default';
}

if ($this->PageLayout->plugin === 'Pages') {
	$panelCss .= ' nc-content-list';
} else {
	$panelCss .= ' nc-content';
}

if (!empty($centerContent) || $containerType === Container::TYPE_MAIN) {
	$domId = ' id="frame-' . $frame['id'] . '"';
} else {
	$domId = '';
	$frameTitle = h($frame['name']);
}

if (!empty($centerContent)) {
	$frameTitle = $this->fetch('frameTitle');
	if (! $frameTitle) {
		$frameTitle = h($frame['name']);
	}
} else {
	$frameTitle = h($frame['name']);
}
?>

<section<?php echo $domId . ' class="frame' . $panelCss . ' plugin-' . strtr($frame['plugin_key'], '_', '-') . '"'; ?>>
	<?php if ($frameTitle || $this->PageLayout->hasBoxSetting($box)) : ?>
		<div class="panel-heading clearfix">
			<?php echo $this->PageLayout->getBlockStatus(true); ?>
			<span><?php echo $frameTitle; ?></span>

			<?php if ($this->PageLayout->hasBoxSetting($box)): ?>
				<div class="pull-right">
					<?php echo $this->element('Frames.order_form', array('frame' => $frame)); ?>
					<?php echo $this->PageLayout->frameSettingLink($frame); ?>
					<?php if (!in_array($containerType, [Container::TYPE_MAJOR, Container::TYPE_MINOR], true) ||
								!SiteSettingUtil::read('App.usage_limit')) : ?>
						<?php echo $this->element('Frames.delete_form', array('frame' => $frame)); ?>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</div>
	<?php endif; ?>

	<div class="<?php echo ($panelCss ? 'panel-body ' : ''); ?>block">
		<?php echo $view; ?>
	</div>
</section>
