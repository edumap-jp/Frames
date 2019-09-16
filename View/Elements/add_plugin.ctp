<?php
/**
 * Element of add page
 *
 * @copyright Copyright 2014, NetCommons Project
 * @author Kohei Teraguchi <kteraguchi@commonsnet.org>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 */

//TODO: configにした方が良い。
$limitPlugins = ['ads'];
?>
<section class="modal fade" id="add-plugin-<?php echo (int)$boxId; ?>" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<?php echo __d('pages', 'Add plugin'); ?>
			</div>

			<div class="modal-body">
				<?php if ($plugins = Current::read('PluginsRoom')) : ?>
				<div class="list-group">
					<?php foreach ($plugins as $plugin) : ?>
						<?php if (! in_array($plugin['Plugin']['key'], $limitPlugins, true) ||
										Current::allowSystemPlugin('site_manager')) : ?>
						<article class="list-group-item clearfix">
							<?php echo $this->NetCommonsForm->create(
									'FrameAdd' . $plugin['Plugin']['id'], array('type' => 'post', 'url' => '/frames/frames/add')
								); ?>
								<div class="pull-left">
									<h4 class="list-group-item-heading clearfix">
										<?php echo h($plugin['Plugin']['name']); ?>

										<div class="dropdown inline-block">
											<a id="plugin-authors-<?php echo (int)$boxId . '-' . $plugin['Plugin']['id']; ?>"
													class="btn btn-info btn-xs"
													type="button" data-toggle="dropdown" aria-expanded="false">
												<span class="glyphicon glyphicon-user"></span>
											</a>
											<ul class="dropdown-menu" role="menu"
												aria-labelledby="plugin-authors-<?php echo (int)$boxId . '-' . $plugin['Plugin']['id']; ?>">

												<?php echo $this->Composer->getAuthors($plugin['Plugin']['key']); ?>
											</ul>
										</div>
									</h4>
									<?php echo $this->Composer->getDescription($plugin['Plugin']['key']); ?>
								</div>
								<div class="pull-right">
									<?php
										echo $this->NetCommonsForm->hidden('Frame.room_id', array(
											'value' => $roomId,
										));
										echo $this->NetCommonsForm->hidden('Frame.language_id', array(
											'value' => Current::read('Language.id'),
										));
										echo $this->NetCommonsForm->hidden('Frame.box_id', array(
											'value' => $boxId,
										));
										echo $this->NetCommonsForm->hidden('Frame.plugin_key', array(
											'value' => $plugin['Plugin']['key'],
										));
										echo $this->NetCommonsForm->hidden('Plugin.name', array(
											'value' => $plugin['Plugin']['name'],
										));
										echo $this->NetCommonsForm->button(
											'<span class="glyphicon glyphicon-plus"> </span>' . __d('net_commons', 'Add'),
											array(
												'class' => 'btn btn-success btn-workflow',
												'name' => 'save'
											)
										);
									?>
								</div>
							<?php echo $this->NetCommonsForm->end(); ?>
						</article>
						<?php endif; ?>
					<?php endforeach; ?>
				</div>
				<?php endif; ?>
			</div>

			<div class="modal-footer">
				<div class="text-center">
					<button class="btn btn-default" data-dismiss="modal" aria-hidden="true">
						<span class="glyphicon glyphicon-remove"></span>
						<?php echo __d('net_commons', 'Close') ?>
					</button>
				</div>
			</div>
		</div>
	</div>
</section>

<div class="add-plugin">
	<button class="btn btn-primary form-control" data-toggle="modal" data-target="#add-plugin-<?php echo (int)$boxId; ?>">
		<span class="glyphicon glyphicon-pushpin"></span>
		<?php if ($containerType === Container::TYPE_HEADER) : ?>
			<?php echo __d('boxes', 'Add plugin to header'); ?>
		<?php elseif ($containerType === Container::TYPE_MAJOR || $containerType === Container::TYPE_MINOR) : ?>
			<?php echo __d('boxes', 'Add plugin'); ?>
		<?php elseif ($containerType === Container::TYPE_FOOTER) : ?>
			<?php echo __d('boxes', 'Add plugin to footer'); ?>
		<?php else : ?>
			<?php echo __d('boxes', 'Add plugin to center'); ?>
		<?php endif; ?>
	</button>
</div>
