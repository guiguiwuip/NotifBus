<?php
App::uses('AppModel', 'Model', 'AuthComponent', 'Controller/Component');
/**
 * User Model
 *
 * @property Arret $Arret
 */
class User extends AppModel {

/**
 * beforeSave method
 * 
 * @param  array  $options 
 * @return boolean
 */
	public function beforeSave($options = array()) {
	    if (isset($this->data[$this->alias]['password'])) {
	        $this->data[$this->alias]['password'] = AuthComponent::password($this->data[$this->alias]['password']);
	    }
	    return true;
	}


/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'username' => array(
			'notempty' => array(
				'rule' => array('notempty'),
			),
		),
		'password' => array(
			'notempty' => array(
				'rule' => array('notempty'),
			),
		),
		'group' => array(
			'numeric' => array(
				'rule' => array('numeric'),
			),
		),
	);


/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Arret' => array(
			'dependent'    => true,
		),
		'Lieux' => array(
			'dependent'    => true,
		)
	);

/**
 * check_if_exist method
 *
 * Vérifie si un utilisateur avec le même pseudo existe déjà avant inscription
 *
 * @param string $pseudo
 * @return boolean
 */
	public function check_if_exist($pseudo = null){

		$user = $this->find('first', array(
			'conditions' => array('User.username' => $pseudo)
			));

		if(!$user)
			return false;
		else
			return true;
	}

}


