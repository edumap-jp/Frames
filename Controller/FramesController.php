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
App::uses('CurrentLibPage', 'NetCommons.Lib/Current');
App::uses('CurrentLibFrame', 'NetCommons.Lib/Current');
App::uses('CurrentLibRoom', 'NetCommons.Lib/Current');

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
 * add method
 *
 * @return void
 */
	public function add() {
		if (! $this->request->is('post')) {
			return $this->throwBadRequest();
		}

		$this->Frame->create();
		$data = $this->data;
		$data['Frame']['is_deleted'] = false;
		$data['FramesLanguage']['name'] = Hash::get(
			$data, 'Plugin.name', __d('frames', 'New frame %s', date('YmdHis'))
		);
		$data['Frame']['room_id'] = Hash::get($data, 'Frame.room_id', 1);
		$data['FramePublicLanguage']['language_id'] = array('0');

		$frame = $this->Frame->saveFrame($data);
		if (! $frame) {
			//エラー処理
			return $this->throwBadRequest();
		}

		$plugin = $this->Plugin->cacheFindQuery('first', [
			'recursive' => 0,
			'conditions' => [
				'Plugin.key' => $data['Frame']['plugin_key']
			],
		]);
		if ($plugin) {
			$pluginKey = $data['Frame']['plugin_key'];
			if (!empty($plugin['Plugin']['frame_add_action'])) {
				list($controller, $action) = explode('/', $plugin['Plugin']['frame_add_action']);
			} elseif (!empty($plugin['Plugin']['default_setting_action'])) {
				list($controller, $action) = explode('/', $plugin['Plugin']['default_setting_action']);
			} else {
				return $this->redirect($this->request->referer(true));
			}

			$url = array(
				'plugin' => $pluginKey,
				'controller' => $controller,
				'action' => $action,
				'?' => array('frame_id' => $frame['Frame']['id'], 'page_id' => Current::read('Page.id')),
				'#' => 'frame-' . $frame['Frame']['id']
			);
			return $this->redirect($url);
		}

		$this->redirect($this->request->referer(true));
	}

/**
 * delete method
 *
 * @return void
 */
	public function delete() {
		if (! $this->request->is('delete')) {
			return $this->throwBadRequest();
		}

		$frame['Frame'] = Current::read('Frame');
		if (! $frame['Frame']) {
			return $this->throwBadRequest();
		}

		$data = Hash::merge($frame, $this->data);
		$data['Frame']['is_deleted'] = true;
		if (! $this->Frame->saveFrame($data)) {
			//エラー処理
			return $this->throwBadRequest();
		}

		$parseUrl = parse_url($this->request->referer(true));
		if (isset($parseUrl['query'])) {
			$match = [];
			if (preg_match('/page_id=([0-9]+)/', $parseUrl['query'], $match)) {
				$pageId = $match[1];
				$page = $this->__findPageById($pageId);
			} elseif (preg_match('/frame_id=([0-9]+)/', $parseUrl['query'], $match)) {
				$frameId = $match[1];
				$page = $this->__findPageByFrameId($frameId);
			}
			if (!empty($page['Page'])) {
				$permalink = $this->__getPageRedirectUrl($page);
				return $this->redirect($permalink);
			}
		}
		$this->redirect($this->request->referer(true));
	}

/**
 * ページのリダイレクトURLを取得
 *
 * @param array $page ページデータ
 * @return string
 */
	private function __getPageRedirectUrl($page) {
		$CurrentLibPage = CurrentLibPage::getInstance();
		if ($CurrentLibPage->isTopPageId($page['Page']['id'])) {
			$permalink = '/';
		} else {
			$CurrentLibRoom = CurrentLibRoom::getInstance();
			$space = $CurrentLibRoom->findSpaceByRoomId($page['Page']['room_id']);
			if (! empty($space['Space']['permalink'])) {
				$permalink = '/' . $space['Space']['permalink'] . '/' . $page['Page']['permalink'];
			} else {
				$permalink = '/' . $page['Page']['permalink'];
			}
		}
		return $permalink;
	}

/**
 * frame_idからページを取得
 *
 * @param int|string $frameId フレームID
 * @return void
 */
	private function __findPageByFrameId($frameId) {
		$CurrentLibFrame = CurrentLibFrame::getInstance();
		$frame = $CurrentLibFrame->findFrameById($frameId);
		if (empty($frame['Frame'])) {
			return false;
		}

		$CurrentLibPage = CurrentLibPage::getInstance();
		$box = $CurrentLibPage->findBoxById($frame['Frame']['box_id']);
		if (empty($box['Box'])) {
			return false;
		}

		$pageId = $box['Box']['page_id'];
		return $this->__findPageById($pageId);
	}

/**
 * ページIDからページ情報を取得
 *
 * @param int|string $pageId ページID
 * @return void
 */
	private function __findPageById($pageId) {
		$CurrentLibPage = CurrentLibPage::getInstance();
		$page = $CurrentLibPage->findPage($pageId);
		return $page;
	}

/**
 * edit method
 *
 * @return void
 */
	public function edit() {
		if (! $this->request->is('put')) {
			return $this->throwBadRequest();
		}

		$frame['Frame'] = Current::read('Frame');
		if (! $frame['Frame']) {
			return $this->throwBadRequest();
		}

		$data = Hash::merge($frame, $this->data);
		if (! $this->Frame->saveFrame($data)) {
			return $this->throwBadRequest();
		}

		if (Hash::get($this->request->data, '_Frame.redirect')) {
			$url = Hash::get($this->request->data, '_Frame.redirect');
		} else {
			$url = $this->request->referer(true);
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
		if (! $this->request->is('put')) {
			return $this->throwBadRequest();
		}

		$frame['Frame'] = Current::read('Frame');
		if (! $frame['Frame']) {
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
		$this->redirect($this->request->referer(true));
	}
}
