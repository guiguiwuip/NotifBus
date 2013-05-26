<?php
/**
 * HoraireFixture
 *
 */
class HoraireFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 50, 'key' => 'primary'),
		'start' => array('type' => 'time', 'null' => false, 'default' => null),
		'end' => array('type' => 'time', 'null' => false, 'default' => null),
		'arret_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 50),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => null),
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
		array(
			'id' => 1,
			'start' => '18:29:57',
			'end' => '18:29:57',
			'arret_id' => 1,
			'created' => '2013-04-06 18:29:57',
			'modified' => '2013-04-06 18:29:57'
		),
	);

}
