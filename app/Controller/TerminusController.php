<?php
App::uses('AppController', 'Controller');
/**
 * Terminus Controller
 *
 * @property Terminus $Terminus
 */
class TerminusController extends AppController {


/** beforFilter method
 *
 * Executed before every action in the controller
 *
 * @return void
 */
    public function beforeFilter() {
	    parent::beforeFilter();
	    $this->Auth->allow('*');
	}


/**
 * options method
 *
 * Renvoi la liste des terminus d'une ligne
 * 
 * @param  string $ligne 
 * @return void
 */
	public function options($ligne = null, $sens = null) {

		$this->layout = false;

		$options = false;

		if($ligne != null)
		{
			$terminus = $this->Terminus->find('all', array(
					'fields' => array('Terminus.idTan', 'Terminus.name'),
					'conditions' => array(
						'Ligne.name'        => $ligne,
						'Terminus.idTan !=' => '',
						'Terminus.sens'     => $sens
					)
				));

			if($terminus){
				$options = array();
				foreach ($terminus as $t) {
					$options[] = array(
						'id'   => $t['Terminus']['idTan'],
						'name' => $t['Terminus']['name']
					);
				}
			}
		}

		$this->set('options', json_encode($options));

	}



/**
 * remplissage method
 *
 * Replissage de la table Terminus
 *
 * @return void
 */
	public function remplissage() {

		$start = false;

		if($start){

			$this->loadModel('Ligne');

			$lignes = $this->Ligne->find('all');

			// $lignes = $this->Ligne->find('all', array(
			// 	'conditions' => array('Ligne.idTan' => 1)
			// 	)
			// );

			//debug($lignes);


			$arretsJson = json_decode(@file_get_contents('https://open.tan.fr/ewp/arrets.json'), true);

			$erreurs = array();
			$lignesDone = array();


			foreach ($lignes as $l) {

				$stop = false;
				
				$lIDTan = $l['Ligne']['idTan'];
				//echo $lIDTan+" ";

				//On cherche un arrêt sur la ligne courante
				$i=0;
				while (!$stop) {

					$lignesArret = $arretsJson[$i]['ligne']; //les lignes passant par l'arrêt

					foreach ($lignesArret as $la) {
						if(!$stop){

							$arret = null;
							$directions = null;
							$ligne = null;


							if($la['numLigne'] == $lIDTan){

								$arret = $arretsJson[$i]['codeLieu'];

								//Les deux sens
								$arretJsonS1 = json_decode(file_get_contents('https://open.tan.fr/ewp/horairesarret.json/'.$arret.'/'.$lIDTan.'/1'), true);
								$arretJsonS2 = json_decode(file_get_contents('https://open.tan.fr/ewp/horairesarret.json/'.$arret.'/'.$lIDTan.'/2'), true);


								$ligne = ($arretJsonS1['ligne']) ? $arretJsonS1['ligne'] : $arretJsonS2['ligne'];

								$directions = array(
										"s1" => $ligne['directionSens1'] ,
										"s2" => $ligne['directionSens2'] 
									);


								//debug($arretJsonS1);
								//debug($arretJsonS2);

								//Vérif pas d'erreur de reconaissance de l'arrêt par l'API
								//cf. ligne 25
								if($ligne != null && $directions['s1'] != null && $directions['s2'] != null)
								{

									$stop = true; //On a un arrêt !

									//echo $arret+" ";
								}
								
							}

						}
					}

					if(count($arretsJson)-1 <= $i){
						$stop = true; //pas d'arrêt pour la ligne, ambetant, non ?
						array_push($erreurs, "Pas d'arrêt pour ligne " . $lIDTan . " (idTan)");
					}
					else
						$i++;
				}


				//On a trouvé un arrêt cool !
				if($arret != null)
				{

					//Des notes pour le sens 1
					if(!empty($arretJsonS1['notes'])) 
					{

						//terminus 1 -> directionsen1
						//terminus 2 -> notes
						
						$notes  = $arretJsonS1['notes'];

						//Gestion des notes
						foreach ($notes as $n) {

							$terminusNote = array(

								'ligne_id' => $l['Ligne']['id'],
								'name'     => $n['libelle'],
								'idTan'    => $n['code'],
								'sens'     => 1

							);

							$terminusNote = array('Terminus' => $terminusNote);

							$this->Terminus->create();
							$this->Terminus->save($terminusNote, false);
						}

						//echo "notesS1 ";

					}


					//Le terminus "officiel" du sens 1, donné par la direction
					$terminus1 = array(

						'ligne_id' => $l['Ligne']['id'],
						'name'     => $directions['s1'],
						'idTan'    => '',
						'sens'     => 1

					);

					$terminus1 = array('Terminus' => $terminus1);

					$this->Terminus->create();
					$this->Terminus->save($terminus1);



					//Des notes pour le sens 2
					if(!empty($arretJsonS2['notes'])) 
					{

						//terminus 1 -> directionsen1
						//terminus 2 -> notes
						
						$notes  = $arretJsonS2['notes'];
							
						//Gestion des notes
						foreach ($notes as $n) {

							$terminusNote = array(
								
								'ligne_id' => $l['Ligne']['id'],
								'name'     => $n['libelle'],
								'idTan'    => $n['code'],
								'sens'     => 2

							);

							$terminusNote = array('Terminus' => $terminusNote);

							$this->Terminus->create();
							$this->Terminus->save($terminusNote, false);
						}

						//echo "notesS2";
						
					}

					
					//Le terminus "officiel" du sens 2, donné par la direction
					$terminus2 = array(

						'ligne_id' => $l['Ligne']['id'],
						'name'     => $directions['s2'],
						'idTan'    => '',
						'sens'     => 2

					);

					$terminus2 = array('Terminus' => $terminus2);

					$this->Terminus->create();
					$this->Terminus->save($terminus2, false);
				}

				array_push($lignesDone, $lIDTan);
			}

			debug($erreurs);

		} else {

			debug('stoped');

		}

	}



}
