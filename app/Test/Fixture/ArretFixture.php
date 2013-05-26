<?php
/**
 * ArretFixture
 *
 */
class ArretFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 50, 'key' => 'primary'),
		'arret' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 10, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'ligne' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 10, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'sens' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 1),
		'user_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 50),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
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
			'arret' => 'Lorem ip',
			'ligne' => 'Lorem ip',
			'sens' => 1,
			'user_id' => 1,
			'modified' => '2013-04-06 18:28:50',
			'created' => '2013-04-06 18:28:50'
		),
	);

}
