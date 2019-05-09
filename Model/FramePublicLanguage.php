<?php
/**
 * FramePublicLanguage Model
 *
 * @property Language $Language
 * @property Frame $Frame
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('FramesAppModel', 'Frames.Model');

/**
 * FramePublicLanguage Model
 *
 * @package NetCommons\Frames\Model
 */
class FramePublicLanguage extends FramesAppModel {

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Language' => array(
			'className' => 'M17n.Language',
			'foreignKey' => 'language_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Frame' => array(
			'className' => 'Frames.Frame',
			'foreignKey' => 'frame_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

/**
 * Called during validation operations, before validation. Please note that custom
 * validation rules can be defined in $validate.
 *
 * @param array $options Options passed from Model::save().
 * @return bool True if validate operation should continue, false to abort
 * @link http://book.cakephp.org/2.0/en/models/callback-methods.html#beforevalidate
 * @see Model::save()
 */
	public function beforeValidate($options = array()) {
		$this->validate = ValidateMerge::merge(array(
			'language_id' => array(
				'numeric' => array(
					'rule' => array('numeric'),
					'message' => __d('net_commons', 'Invalid request.'),
				),
			),
			'frame_id' => array(
				'numeric' => array(
					'rule' => array('numeric'),
					'message' => __d('net_commons', 'Invalid request.'),
				),
			),
			'is_public' => array(
				'boolean' => array(
					'rule' => array('boolean'),
					'message' => __d('net_commons', 'Invalid request.'),
				),
			),
		), $this->validate);

		return parent::beforeValidate($options);
	}

/**
 * ページでのフレーム表示有無
 * 呼び出しもとでトランザクションを開始する
 *
 * @param array $data リクエストデータ
 * @throws InternalErrorException
 * @return mixed On success Model::$data if its not empty or true, false on failure
 */
	public function savePublicLang($data) {
		if (! array_key_exists('FramePublicLanguage', $data)) {
			return true;
		}

		$activeLangs = $this->Language->getLanguages();
		if (! $data['FramePublicLanguage']['language_id']) {
			$allChecked = false;
		} elseif (in_array('0', $data['FramePublicLanguage']['language_id'], true)) {
			$allChecked = true;
		} else {
			$allChecked = !(bool)array_diff(
				Hash::extract($activeLangs, '{n}.Language.id'),
				$data['FramePublicLanguage']['language_id']
			);
		}

		if ($allChecked) {
			return $this->_savePublicLangByAllChecked($data);
		} else {
			return $this->_savePublicLangByNotAllChecked($data);
		}
	}

/**
 * ページでのフレーム表示有無
 * 呼び出しもとでトランザクションを開始する
 *
 * @param array $data リクエストデータ
 * @throws InternalErrorException
 * @return mixed On success Model::$data if its not empty or true, false on failure
 */
	protected function _savePublicLangByAllChecked($data) {
		//既存データのis_publicをtrueにする
		$conditions = array(
			'FramePublicLanguage.frame_id' => $data['Frame']['id'],
			'FramePublicLanguage.language_id !=' => '0'
		);
		if (! $this->deleteAll($conditions, false)) {
			throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
		}

		//language_id=0のデータ作成
		$count = $this->find('count', array(
			'recursive' => -1,
			'conditions' => array(
				'FramePublicLanguage.frame_id' => $data['Frame']['id'],
				'FramePublicLanguage.language_id' => '0'
			)
		));
		if (! $count) {
			$this->create(false);
			$create = $this->create(array(
				'id' => null,
				'language_id' => '0',
				'frame_id' => $data['Frame']['id'],
				'is_public' => true
			));

			if (! $this->save($create)) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}
		}

		return true;
	}

/**
 * ページでのフレーム表示有無
 * 呼び出しもとでトランザクションを開始する
 *
 * @param array $data リクエストデータ
 * @throws InternalErrorException
 * @return mixed On success Model::$data if its not empty or true, false on failure
 */
	protected function _savePublicLangByNotAllChecked($data) {
		//language_id=0のデータ削除
		$conditions = array(
			'FramePublicLanguage.frame_id' => $data['Frame']['id'],
			'FramePublicLanguage.language_id' => '0'
		);
		if (! $this->deleteAll($conditions, false)) {
			throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
		}

		//チェックしているlanguage_idのデータのis_publicをtrueにする
		$update = array(
			'FramePublicLanguage.is_public' => true,
		);
		$conditions = array(
			'FramePublicLanguage.frame_id' => $data['Frame']['id'],
			'FramePublicLanguage.language_id' => $data['FramePublicLanguage']['language_id']
		);
		if (! $this->updateAll($update, $conditions)) {
			throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
		}

		//チェックしているlanguage_idのデータ以外のis_publicをfalseにする
		$update = array(
			'FramePublicLanguage.is_public' => false,
		);
		$conditions = array(
			'FramePublicLanguage.frame_id' => $data['Frame']['id'],
			'FramePublicLanguage.language_id NOT IN' => $data['FramePublicLanguage']['language_id']
		);
		if (! $this->updateAll($update, $conditions)) {
			throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
		}

		$inserts = $this->Language->find('list', array(
			'recursive' => -1,
			'fields' => array('Language.id', 'Language.id'),
			'conditions' => array(
				'FramePublicLanguage.language_id' => null,
				'Language.id' => $data['FramePublicLanguage']['language_id'],
			),
			'joins' => array(
				array(
					'table' => $this->table,
					'alias' => $this->alias,
					'type' => 'LEFT',
					'conditions' => array(
						$this->alias . '.language_id = Language.id',
						$this->alias . '.frame_id' => $data['Frame']['id'],
					),
				),
			),
		));

		foreach ($inserts as $langId) {
			$this->create(false);
			$create = $this->create(array(
				'id' => null,
				'language_id' => $langId,
				'frame_id' => $data['Frame']['id'],
				'is_public' => true
			));

			if (! $this->save($create)) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}
		}

		return true;
	}

}
