<?php
App::uses('AppController', 'Controller');
/**
 * Lignes Controller
 *
 */
class LignesController extends AppController {

/**
 * beforeFilter method
 *
 * Appelé avant chaque fonction
 *
 * @see AppController
 * @return void
 */
	public function beforeFilter() {
	    parent::beforeFilter();
	}


/**
 * lignes method
 *
 * Renvoi la liste des lignes passant par un arrêt
 *
 * @param  $arret
 * @return  void
 */
	public function lignes($arret = null) {

		$this->layout = false;

		$lignes = $this->Ligne->lignes($arret);

		//debug($lignes);

		$this->set('lignes', json_encode($lignes));
	}

/**
 * sens method
 *
 * Renvoi la liste des sens d'une lignes
 * *
 * @param  $ligne 
 * @return void
 */
	public function sens($ligne = null) {

		$this->layout = false;

		$directions = null;

		if($ligne != null)
		{
			$infoLigne = $this->Ligne->find('first', array(
					'conditions' => array('Ligne.name' => $ligne)
				));
			if($infoLigne){
				$directions = array(
					0 => array(
							'id'   => '1',
							'text' => 'Vers '.$infoLigne['Ligne']['sens_1']
						),
					1 => array(
							'id'   => '2',
							'text' => 'Vers '.$infoLigne['Ligne']['sens_2']
						)
				);
			}
		}
		
		$this->set('directions', json_encode($directions));
	}


}
