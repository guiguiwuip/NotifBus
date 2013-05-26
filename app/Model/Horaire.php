<?php
App::uses('AppModel', 'Model');
/**
 * Horaire Model
 *
 * @property Arret $Arret
 */
class Horaire extends AppModel {

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'start' => array(
			'time' => array(
				'rule' => array('time'),
			),
		),
		'end' => array(
			'time' => array(
				'rule' => array('time'),
			),
		),
		'arret_id' => array(
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
	public $belongsTo = array('Arret');
}
