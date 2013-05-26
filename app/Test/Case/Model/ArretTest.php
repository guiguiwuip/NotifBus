<?php
App::uses('Arret', 'Model');

/**
 * Arret Test Case
 *
 */
class ArretTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.arret',
		'app.user',
		'app.horaire',
		'app.lieux'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Arret = ClassRegistry::init('Arret');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Arret);

		parent::tearDown();
	}

}
