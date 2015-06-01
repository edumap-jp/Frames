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

<?php echo $this->Form->create('', array(
		'type' => 'delete',
		'class' => 'frame-btn pull-left',
		'url' => '/frames/frames/delete/' . $frame['id']
	)); ?>

	<?php echo $this->Form->hidden('Frame.id', array(
			'value' => $frame['id'],
		)); ?>

	<?php echo $this->Form->button('<span class="glyphicon glyphicon-remove"></span><span class="sr-only">' . __d('frames', 'Delete frame') . '</span>', array(
			'name' => 'delete',
			'class' => 'btn btn-default',
			'onclick' => 'return confirm(\'' . __d('frames', 'Do you want to delete the frame?') . '\')'
		)); ?>
<?php echo $this->Form->end();