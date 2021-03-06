<?php
/**
 * Initial data generation of Migration
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsMigration', 'NetCommons.Config/Migration');

/**
 * Initial data generation of Migration
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Frames\Config\Migration
 */
class Records extends NetCommonsMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'records';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(),
		'down' => array(),
	);

/**
 * Records keyed by model name.
 *
 * @var array $records
 */
	public $records = array(
		'Frame' => array(
			//日本語
			array(
				'id' => '1',
				'language_id' => '2',
				'room_id' => '1',
				'box_id' => '16',
				'plugin_key' => 'announcements',
				'block_id' => '1',
				'key' => 'frame_1',
				'name' => 'お知らせ',
				'weight' => '1',
				'is_published' => true,
				'from' => null,
				'to' => null,
				'is_deleted' => false,
			),
			array(
				'id' => '2',
				'language_id' => '2',
				'room_id' => '1',
				'box_id' => '2',
				'plugin_key' => 'menus',
				'block_id' => '2',
				'key' => 'frame_2',
				'name' => 'メニュー',
				'weight' => '1',
				'is_published' => true,
				'from' => null,
				'to' => null,
				'is_deleted' => false,
			),
			//英語
			array(
				'id' => '3',
				'language_id' => '1',
				'room_id' => '1',
				'box_id' => '16',
				'plugin_key' => 'announcements',
				'block_id' => '3',
				'key' => 'frame_1',
				'name' => 'Announcement',
				'weight' => '1',
				'is_published' => true,
				'from' => null,
				'to' => null,
				'is_deleted' => false,
			),
			array(
				'id' => '4',
				'language_id' => '1',
				'room_id' => '1',
				'box_id' => '2',
				'plugin_key' => 'menus',
				'block_id' => '2',
				'key' => 'frame_2',
				'name' => 'Menu',
				'weight' => '1',
				'is_published' => true,
				'from' => null,
				'to' => null,
				'is_deleted' => false,
			),
		),
	);

/**
 * Before migration callback
 *
 * @param string $direction Direction of migration process (up or down)
 * @return bool Should process continue
 */
	public function before($direction) {
		return true;
	}

/**
 * After migration callback
 *
 * @param string $direction Direction of migration process (up or down)
 * @return bool Should process continue
 */
	public function after($direction) {
		return parent::updateAndDeleteRecords($direction);
	}
}
