<?php
App::uses('Ligne', 'Model');

/**
 * Ligne Test Case
 *
 */
class LigneTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.ligne'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Ligne = ClassRegistry::init('Ligne');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Ligne);

		parent::tearDown();
	}

}
