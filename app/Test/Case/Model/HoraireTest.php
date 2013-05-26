<?php
App::uses('Horaire', 'Model');

/**
 * Horaire Test Case
 *
 */
class HoraireTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.horaire',
		'app.arret',
		'app.user',
		'app.lieux'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Horaire = ClassRegistry::init('Horaire');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Horaire);

		parent::tearDown();
	}

}
