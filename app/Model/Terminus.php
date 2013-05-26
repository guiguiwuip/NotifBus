<?php
App::uses('AppModel', 'Model');
/**
 * Terminus Model
 *
 * @property Ligne $Ligne
 */
class Terminus extends AppModel {


	public $useTable = 'terminus';

/**
 * Validation rules
 *
 * @var array
 */
	// public $validate = array(
	// 	'ligne_id' => array(
	// 		'numeric' => array(
	// 			'rule' => array('numeric'),
	// 		),
	// 	),
	// 	'name' => array(
	// 		'notempty' => array(
	// 			'rule' => array('notempty'),
	// 		),
	// 	),
	// 	// 'idTan' => array(
	// 	// 	'notempty' => array(
	// 	// 		'rule' => array('notempty'),
	// 	// 	),
	// 	// ),
	// 	'sens' => array(
	// 		'numeric' => array(
	// 			'rule' => array('numeric'),
	// 		),
	// 	),
	// );


/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Ligne' => array(
			'className' => 'Ligne',
			'foreignKey' => 'ligne_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
