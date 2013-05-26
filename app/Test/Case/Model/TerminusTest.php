<?php
App::uses('Terminus', 'Model');

/**
 * Terminus Test Case
 *
 */
class TerminusTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.terminus',
		'app.ligne'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Terminus = ClassRegistry::init('Terminus');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Terminus);

		parent::tearDown();
	}

}
