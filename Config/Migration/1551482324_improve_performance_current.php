<?php
/**
 * Currentライブラリの速度改善
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsMigration', 'NetCommons.Config/Migration');

/**
 * Currentライブラリの速度改善
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Blocks\Config\Migration
 */
class ImprovePerformanceCurrent extends NetCommonsMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'improve_performance_current';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
			'drop_field' => array(
				'frames' => array('indexes' => array('box_id')),
			),
		),
		'down' => array(
			'create_field' => array(
				'frames' => array(
					'indexes' => array(
						'box_id' => array('column' => 'box_id', 'unique' => 0),
					),
				),
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
