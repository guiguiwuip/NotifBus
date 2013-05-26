<?php
App::uses('AppModel', 'Model');
/**
 * Ligne Model
 *
 */
class Ligne extends AppModel {

	public $useTable = 'lignes';

	

/**
* lignes method
*
* Renvoi la liste des lignes passant par un arrêt
*
* @param $arret
* @return array
*/
	public function lignes($arret = null) {
		$lignes = null;

		$idTan = $arret;

		//On va chercher les lignes en dtb
		$lignesDtb = $this->find('all');

		if ($idTan) {
			//On cherche tous les arrêts dans l'API qui disposent d'infos sur lignes
			if ($arretsJson = json_decode(@file_get_contents('https://open.tan.fr/ewp/arrets.json'), true)) 
			{
				//On boucle sur tous les arrêts
				foreach ($arretsJson as $aJ) {

					//Si l'arret corespond à celui recherché
					if($aJ['codeLieu'] == $idTan) 
					{
						//On stocke les lignes qui passe à cet arrêt
						$lignesJson = $aJ['ligne'];

						//Travail sur les lignes :
						
						$lignes = array();

						//Pour chaque ligne json, on cherche l'équivalente en dtb
						foreach ($lignesJson as $lJ) {

							$name = false;
							$id = false;
							foreach ($lignesDtb as $lD) 
							{
								$stop = false;
								if($lD['Ligne']['idTan'] == $lJ['numLigne'] && !$stop) {
									$name = $lD['Ligne']['name'];
									$id   = $lD['Ligne']['id'];
									$stop = true; //On a trouvé !
								}
							}

							//Ajout de la ligne 
							$lignes[] = array(
								"id" => $id,
								"text"  => ($name) ?: $lJ['numLigne']
							); 
						}
					}
				}
			}
		}
		return $lignes;
	}


/**
* sens method
*
* Renvoi les 2 sens d'une ligne passant par un arrêt
*
* @param $ligne
* @return array
*/
	public function sens($ligne = null) {

		$sens = null;

		if($ligne != null)
		{
			//On cherche la ligne en dtb
			$infoLigne = $this->find('all', array(
					'conditions' => array('Ligne.name' => $ligne)
				));

			debug($infoLigne);

			if(!empty($infoLigne)){

				$sens = array(
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

		return $sens;
	}


/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Terminus' => array(
			'className'    => 'Terminus',
			'foreignKey'   => 'ligne_id',
			'dependent'    => false,
			'conditions'   => '',
			'fields'       => '',
			'order'        => '',
			'limit'        => '',
			'offset'       => '',
			'exclusive'    => '',
			'finderQuery'  => '',
			'counterQuery' => ''
		),
		'Arrets' => array(
			'className'    => 'Arrets',
			'foreignKey'   => 'ligne_id',
			'dependent'    => false,
			'conditions'   => '',
			'fields'       => '',
			'order'        => '',
			'limit'        => '',
			'offset'       => '',
			'exclusive'    => '',
			'finderQuery'  => '',
			'counterQuery' => ''
		)
	);

}
