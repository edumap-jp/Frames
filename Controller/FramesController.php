<?php
/**
 * Frames Controller
 *
 * @property Frame $Frame
 *
 * @copyright Copyright 2014, NetCommons Project
 * @author Kohei Teraguchi <kteraguchi@commonsnet.org>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 */

App::uses('FramesAppController', 'Frames.Controller');

/**
 * Frames Controller
 *
 * @author Kohei Teraguchi <kteraguchi@commonsnet.org>
 * @package NetCommons\Frames\Controller
 */
class FramesController extends FramesAppController {

/**
 * uses
 *
 * @var array
 */
	public $uses = array(
		'Frames.Frame',
		'Pages.Page',
		'PluginManager.Plugin'
	);

/**
 * use component
 *
 * @var array
 */
	public $components = array(
		'NetCommons.Permission' => array(
			//アクセスの権限
			'allow' => array(
				'add,edit,delete,order' => 'page_editable',
			),
		),
		'Pages.PageLayout',
		'Security',
	);

/**
 * index method
 *
 * @param string $id frameId
 * @throws NotFoundException
 * @return void
 */
	public function index($id = null) {
		$frame = $this->Frame->findById($id);
		if (empty($frame)) {
			throw new NotFoundException();
		}

		$frame['Frame']['Plugin'] = $frame['Plugin'];
		$frame['Frame']['Language'] = $frame['Language'];
		unset($frame['Plugin'], $frame['Language']);

		$this->set('frames', array($frame['Frame']));

		$plugins = $this->Plugin->find('all', array(
			'recursive' => -1,
			'conditions' => array('language_id' => Current::read('Language.id'))
		));
		$pluginMap = Hash::combine($plugins, '{n}.Plugin.key', '{n}.Plugin');
		$this->set('pluginMap', $pluginMap);
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		$this->request->onlyAllow('post');

		$this->Frame->create();
		$data = $this->data;
		$data['Frame']['is_deleted'] = false;
		$data['Frame']['name'] = __d('frames', 'New frame %s', date('YmdHis'));
		$data['Frame']['room_id'] = Hash::get($data, 'Frame.room_id', 1);

		if (! $frame = $this->Frame->saveFrame($data)) {
			//エラー処理
			return $this->throwBadRequest();
		}

		if ($plugin = $this->Plugin->findByKey($data['Frame']['plugin_key'])) {
			if (Hash::get($plugin, 'Plugin.frame_add_action')) {
				$url = '/' . $data['Frame']['plugin_key'] . '/' . $plugin['Plugin']['frame_add_action'];
				return $this->redirect($url . '?frame_id=' . $frame['Frame']['id']);
			} elseif (Hash::get($plugin, 'Plugin.default_setting_action')) {
				$url = '/' . $data['Frame']['plugin_key'] . '/' . $plugin['Plugin']['default_setting_action'];
				return $this->redirect($url . '?frame_id=' . $frame['Frame']['id']);
			}
		}

		$this->redirect($this->request->referer());
	}

/**
 * delete method
 *
 * @return void
 */
	public function delete() {
		$this->request->onlyAllow('delete');

		$this->Frame->setDataSource('master');
		if (! $frame['Frame'] = Current::read('Frame')) {
			return $this->throwBadRequest();
		}

		$data = Hash::merge($frame, $this->data);
		$data['Frame']['is_deleted'] = true;
		if (! $this->Frame->saveFrame($data)) {
			//エラー処理
			return $this->throwBadRequest();
		}

		$this->redirect($this->request->referer());
	}

/**
 * edit method
 *
 * @return void
 */
	public function edit() {
		$this->request->onlyAllow('post');

		if (! $frame['Frame'] = Current::read('Frame')) {
			return $this->throwBadRequest();
		}

		$data = Hash::merge($frame, $this->data);
		if (! $this->Frame->saveFrame($data)) {
			return $this->throwBadRequest();
		}

		if (Hash::get($this->request->data, '_Frame.redirect')) {
			$url = Hash::get($this->request->data, '_Frame.redirect');
		} else {
			$url = $this->request->referer();
			$this->NetCommons->setFlashNotification(
				__d('net_commons', 'Successfully saved.'), array('class' => 'success')
			);
		}

		$this->redirect($url);
	}

/**
 * order method
 *
 * @return void
 */
	public function order() {
		$this->request->onlyAllow('put');

		if (! $frame['Frame'] = Current::read('Frame')) {
			return $this->throwBadRequest();
		}

		if (array_key_exists('up', $this->data)) {
			$order = 'up';
		} elseif (array_key_exists('down', $this->data)) {
			$order = 'down';
		} else {
			return $this->throwBadRequest();
		}

		if (! $this->Frame->saveWeight($frame, $order)) {
			return $this->throwBadRequest();
		}
		$this->redirect($this->request->referer());
	}
}
