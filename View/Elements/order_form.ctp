<?php
/**
 * Element of frame delete
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */
?>

<?php echo $this->NetCommonsForm->create('Frame', array(
		'type' => 'put',
		'class' => 'frame-btn pull-left',
		'url' => NetCommonsUrl::actionUrl(array('plugin' => 'frames', 'controller' => 'frames', 'action' => 'order'))
	)); ?>

	<?php echo $this->NetCommonsForm->hidden('Frame.id', array(
			'value' => $frame['id'],
		)); ?>

	<?php echo $this->NetCommonsForm->hidden('Frame.box_id', array(
			'value' => $frame['box_id'],
		)); ?>

	<?php echo $this->PageLayout->frameOrderButton('up'); ?>

	<?php echo $this->PageLayout->frameOrderButton('down'); ?>
<?php echo $this->NetCommonsForm->end();
