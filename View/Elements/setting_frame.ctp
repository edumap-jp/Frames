<?php
/**
 * Frame setting layout
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

echo $this->NetCommonsHtml->css([
	'/blocks/css/style.css',
	'/frames/css/style.css',
]);
echo $this->NetCommonsHtml->script('/frames/js/frames.js');

$frame = Current::read('Frame');
$frameLang = Current::read('FramesLanguage', array());
$frameLang = Hash::remove($frameLang, 'id');
$frame = Hash::merge($frame, $frameLang);
?>

<section id="frame-<?php echo $frame['id']; ?>" class="frame panel panel-{{frame.headerType}}" ng-cloak>
	<div class="panel-heading clearfix">

		<div class="pull-right">
			<?php echo $this->PageLayout->frameSettingQuitLink(); ?>
		</div>

		<?php echo $this->NetCommonsForm->create('Frame',
			array(
				'name' => 'frameForm',
				'novalidate' => true,
				'type' => 'put',
				'url' => NetCommonsUrl::actionUrl(array('plugin' => 'frames', 'controller' => 'frames', 'action' => 'edit')),
			)); ?>

			<?php echo $this->NetCommonsForm->hidden('Frame.id', array(
					'value' => Current::read('Frame.id'),
				)); ?>

			<?php echo $this->NetCommonsForm->hidden('FramesLanguage.id', array(
					'value' => Current::read('FramesLanguage.id'),
				)); ?>

			<?php echo $this->NetCommonsForm->input('FramesLanguage.name',
				array(
					'label' => false,
					'class' => 'form-control frame-name-setting',
					'error' => false,
					'div' => false,
					'value' => $frame['name']
				)); ?>

			<?php
				echo $this->NetCommonsForm->hidden('Frame.header_type', array(
					'value' => $frame['header_type'],
					'error' => false,
				));
				$this->NetCommonsForm->unlockField('Frame.header_type');
			?>

			<?php echo $this->NetCommonsForm->hidden('_Frame.redirect', array(
					'value' => NetCommonsUrl::backToPageUrl(true)
				)); ?>

			<div class="btn-group">
				<button type="button" data-toggle="dropdown" class="btn btn-default dropdown-toggle frame-header-type-btn">
					<div class="panel panel-{{frame.headerType}} frame-header-type-settings">
						<div class="panel-heading">
							<small>{{frame.headerType}}</small>
						</div>
					</div>
					<span class="caret"></span>
				</button>
				<?php
					$headerTypes = array(
						['key' => 'none', 'name' => __d('frames', 'none')],
						['key' => 'default', 'name' => __d('frames', 'default')],
						['key' => 'primary', 'name' => __d('frames', 'primary')],
						['key' => 'info', 'name' => __d('frames', 'info')],
						['key' => 'success', 'name' => __d('frames', 'success')],
						['key' => 'warning', 'name' => __d('frames', 'warning')],
						['key' => 'danger', 'name' => __d('frames', 'danger')],
					);
				?>
				<ul role="menu" class="dropdown-menu" ng-init="headerTypes = <?php echo h(json_encode($headerTypes)); ?>">
					<li ng-repeat="headerType in headerTypes" ng-click="selectHeaderType(headerType.key)">
						<a href="">
							<div class="panel panel-{{headerType.key}} frame-header-type-settings">
								<div class="panel-heading text-center">
									<small>{{headerType.name}}</small>
								</div>
							</div>
						</a>
					</li>
				</ul>
			</div>

			<?php if (! empty($frameLangs) && count($frameLangs) > 1) : ?>
				<div class="btn-group">
					<button type="button" data-toggle="dropdown" class="btn btn-default dropdown-toggle select-frame-language-btn">
						<?php echo __d('frames', 'Select languages'); ?>
						<?php echo $this->NetCommonsForm->hidden('FramePublicLanguage.language_id', ['value' => '0']); ?>
						<span class="caret"></span>
					</button>
					<ul role="select-frame-language" class="dropdown-menu select-frame-language">
						<?php foreach ($frameLangs as $langId => $frameLangLabel) : ?>
							<li>
								<?php echo $this->NetCommonsForm->checkbox('FramePublicLanguage.language_id.' . $langId, array(
									'label' => $frameLangLabel,
									'value' => $langId,
									'checked' => in_array($langId, $framePublicLangs, true),
									'error' => false,
									'hiddenField' => false,
									'div' => array('class' => 'frame-public-lang-outer'),
								)); ?>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
			<?php endif; ?>

			<button class="btn btn-default" ng-click="sending=true" ng-disabled="sending" onclick="submit(); return false;">
				<?php echo __d('net_commons', 'OK'); ?>
			</button>
		<?php echo $this->NetCommonsForm->end(); ?>
	</div>
	<div class="panel-body block">
		<?php echo $view; ?>
	</div>
</section>

<?php echo $this->NetCommonsHtml->scriptStart(array('inline' => false)); ?>
$(document).ready(function() {
    $(".dropdown-menu.select-frame-language").click(function(e) {
        e.stopPropagation();
    });
});
<?php echo $this->NetCommonsHtml->scriptEnd();
