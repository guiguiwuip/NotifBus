<?php
App::uses('Lieux', 'Model');

/**
 * Lieux Test Case
 *
 */
class LieuxTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.lieux',
		'app.arret',
		'app.user',
		'app.horaire'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Lieux = ClassRegistry::init('Lieux');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Lieux);

		parent::tearDown();
	}

}
