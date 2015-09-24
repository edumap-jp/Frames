<?php
/**
 * FrameFixture
 *
 * @copyright Copyright 2014, NetCommons Project
 * @author Kohei Teraguchi <kteraguchi@commonsnet.org>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 */

/**
 * FrameFixture
 *
 * @author Kohei Teraguchi <kteraguchi@commonsnet.org>
 * @package NetCommons\Blocks\Test\Fixture
 */
class FrameFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'language_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 6),
		'room_id' => array('type' => 'integer', 'null' => true, 'default' => null),
		'box_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'plugin_key' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'block_id' => array('type' => 'integer', 'null' => true, 'default' => null),
		'key' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => 'Key of the frame.', 'charset' => 'utf8'),
		'name' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => 'Name of the frame.', 'charset' => 'utf8'),
		'header_type' => array('type' => 'string', 'null' => false, 'default' => 'default', 'collate' => 'utf8_general_ci', 'comment' => 'Header type of the frame.', 'charset' => 'utf8'),
		'weight' => array('type' => 'integer', 'null' => true, 'default' => null, 'comment' => 'Display order.'),
		'is_deleted' => array('type' => 'boolean', 'null' => false, 'default' => null),
		'created_user' => array('type' => 'integer', 'null' => true, 'default' => null),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'modified_user' => array('type' => 'integer', 'null' => true, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		//メイン
		array(
			'id' => '5',
			'language_id' => '1',
			'room_id' => '1',
			'box_id' => '3',
			'plugin_key' => 'frames',
			'block_id' => '1',
			'key' => 'frame_3',
			'name' => 'Test frame main',
			'weight' => '1',
			'is_deleted' => '0',
		),
		array(
			'id' => '6',
			'language_id' => '2',
			'room_id' => '1',
			'box_id' => '3',
			'plugin_key' => 'frames',
			'block_id' => '2',
			'key' => 'frame_3',
			'name' => 'Test frame main',
			'weight' => '1',
			'is_deleted' => '0',
		),
		//フレーム削除（論理削除）
		array(
			'id' => '11',
			'language_id' => '1',
			'room_id' => '1',
			'box_id' => '3',
			'plugin_key' => 'frames',
			'block_id' => '1',
			'key' => 'frame_6',
			'name' => 'Test frame main 2',
			'weight' => '2',
			'is_deleted' => '1',
		),
		array(
			'id' => '12',
			'language_id' => '2',
			'room_id' => '1',
			'box_id' => '3',
			'plugin_key' => 'frames',
			'block_id' => '2',
			'key' => 'frame_6',
			'name' => 'Test frame main 2',
			'weight' => '2',
			'is_deleted' => '1',
		),
		//フレームのブロックなし
		array(
			'id' => '13',
			'language_id' => '1',
			'room_id' => '1',
			'box_id' => '3',
			'plugin_key' => 'frames',
			'block_id' => null,
			'key' => 'frame_7',
			'name' => 'Test frame main 3',
			'weight' => '2',
			'is_deleted' => '0',
		),
		array(
			'id' => '14',
			'language_id' => '2',
			'room_id' => '1',
			'box_id' => '3',
			'plugin_key' => 'frames',
			'block_id' => null,
			'key' => 'frame_7',
			'name' => 'Test frame main 3',
			'weight' => '2',
			'is_deleted' => '0',
		),
	);

/**
 * Initialize the fixture.
 *
 * @return void
 */
	public function init() {
		if (class_exists('NetCommonsCakeTestCase')) {
			$records = array_keys($this->records);
			foreach ($records as $i) {
				$this->records[$i]['plugin_key'] = NetCommonsCakeTestCase::$plugin;
			}
		}
		parent::init();
	}

}
