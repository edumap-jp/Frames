<?php
/**
 * 移行速度UPのためのIndex追加
 */
App::uses('NetCommonsMigration', 'NetCommons.Config/Migration');

/**
 * Class AddIndexForFrame
 */
class AddIndexForFrame extends NetCommonsMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'add_index_for_frame';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
			'alter_field' => array(
				'frames' => array(
					'room_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false, 'key' => 'index'),
					'block_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false, 'key' => 'index'),
				),
			),
			'create_field' => array(
				'frames' => array(
					'indexes' => array(
						'block_id' => array('column' => 'block_id', 'unique' => 0),
						'room_id' => array('column' => array('room_id', 'plugin_key'), 'unique' => 0),
					),
				),
			),
		),
		'down' => array(
			'alter_field' => array(
				'frames' => array(
					'room_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
					'block_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
				),
			),
			'drop_field' => array(
				'frames' => array('indexes' => array('block_id', 'room_id')),
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
		return true;
	}
}
