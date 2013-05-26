<?php
App::uses('AppModel', 'Model');
/**
 * Lieux Model
 *
 * @property Arret $Arret
 */
class Lieux extends AppModel {


	public $useTable = 'lieux';

/**
 * 
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'regle' => array(
			'notempty' => array(
				'rule' => array('notempty'),
			),
		),
		'lat' => array(
			'notempty' => array(
				'rule' => array('notempty'),
			),
		),
		'lng' => array(
			'notempty' => array(
				'rule' => array('notempty'),
			),
		),
		'user_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
			),
		),
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array('User');

/**
 *  hasMany associations
 *
 * @var array
 */
	public $hasMany = array('Arret');

	
}
