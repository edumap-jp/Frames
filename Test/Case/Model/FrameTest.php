<?php
/**
 * Frame Test Case
 *
 * @copyright Copyright 2014, NetCommons Project
 * @author Kohei Teraguchi <kteraguchi@commonsnet.org>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 */

App::uses('NetCommonsBlockComponent', 'NetCommons.Controller/Component');
App::uses('NetCommonsRoomRoleComponent', 'NetCommons.Controller/Component');
App::uses('YACakeTestCase', 'NetCommons.TestSuite');

App::uses('Frame', 'Frames.Model');

/**
 * Summary for Frame Test Case
 *
 * @author Kohei Teraguchi <kteraguchi@commonsnet.org>
 * @package NetCommons\Frames\Test\Case\Model
 */
class FrameTest extends YACakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.frames.frame',
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Frame = ClassRegistry::init('Frames.Frame');

		YACakeTestCase::loadTestPlugin($this, 'Frames', 'ModelWithAfterFrameSaveTestPlugin');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Frame);

		parent::tearDown();
	}

/**
 * testGetContainableQuery method
 *
 * @return void
 */
	public function testGetContainableQuery() {
		$containableQuery = $this->Frame->getContainableQuery();

		//$query配列の中身(conditions, order)
		$this->assertCount(2, $containableQuery);

		$this->assertArrayHasKey('order', $containableQuery);
		$this->assertCount(1, $containableQuery['order']);
		$this->assertContains('Frame.weight', $containableQuery['order']);
	}

/**
 * testSaveFrame method
 *
 * @return void
 */
	public function testSaveFrame() {
		$expectCount = $this->Frame->find('count', array('recursive' => -1)) + 1;

		$data = array(
			'Frame' => array(
				'is_deleted' => false,
				'name' => '',
				'room_id' => null,
				'plugin_key' => 'model_with_after_frame_save_test_plugin',
				'box_id' => '1'
			)
		);

		$this->Frame->create();
		$this->Frame->saveFrame($data);

		$this->assertEquals($expectCount, $this->Frame->find('count', array('recursive' => -1)));
	}

/**
 * testSaveFrameError method
 *
 * @return void
 */
	public function testSaveFrameError() {
		$this->setExpectedException('InternalErrorException');

		$frameMock = $this->getMockForModel('Frames.Frame', array('save'));
		$frameMock->expects($this->once())
			->method('save')
			->will($this->returnValue(false));

		$expectCount = $frameMock->find('count', array('recursive' => -1));

		$data = array(
			'Frame' => array(
				'is_deleted' => false,
				'name' => '',
				'room_id' => null,
				'plugin_key' => 'model_with_after_frame_save_test_plugin',
				'box_id' => '1'
			)
		);

		$frameMock->create();
		$this->assertFalse($frameMock->saveFrame($data));

		//$this->assertEquals('master', $this->Frame->useDbConfig);
		$this->assertEquals($expectCount, $frameMock->find('count', array('recursive' => -1)));
	}

/**
 * testDeleteFrame method
 *
 * @return void
 */
	public function testDeleteFrame() {
		$expectCount = $this->Frame->find('count', array('recursive' => -1)) - 1;

		$this->Frame->id = 10;
		$this->Frame->deleteFrame();

		//$this->assertEquals('master', $this->Frame->useDbConfig);
		$this->assertEquals($expectCount, $this->Frame->find('count', array('recursive' => -1)));
		$this->assertEmpty($this->Frame->findById('10'));
	}

/**
 * testDeleteFrameError method
 *
 * @return void
 */
	public function testDeleteFrameError() {
		$this->setExpectedException('InternalErrorException');

		$frameMock = $this->getMockForModel('Frames.Frame', array('delete'));
		$frameMock->expects($this->once())
			->method('delete')
			->will($this->returnValue(false));

		$expectCount = $frameMock->find('count', array('recursive' => -1));

		$frameMock->id = 10;
		$this->assertFalse($frameMock->deleteFrame());

		//$this->assertEquals('master', $this->Frame->useDbConfig);
		$this->assertEquals($expectCount, $frameMock->find('count', array('recursive' => -1)));
	}

}
