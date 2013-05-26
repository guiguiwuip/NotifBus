<?php
App::uses('AppModel', 'Model');
/**
 * Arret Model
 *
 * @property User $User
 * @property Horaire $Horaire
 * @property Lieux $Lieux
 */
class Arret extends AppModel {


/**
 * arretsTan method
 *
 * Renvoi liste des arrets Tan
 *
 * @param $maj
 */
	public function arretsTan($maj = false) {
		
		$arrets = false;

		//$arrets va être de la forme :
		// $arrets = array(
		//		'idTan' => 'name'
		//		...
		// );

		$row = 0;
		//On va chercher les arrêts dans l'API
		if ($arretsJson = json_decode(@file_get_contents('https://open.tan.fr/ewp/arrets.json'), true)) {
			$arrets = array();	
			foreach ($arretsJson as $aJ) {
				//Besoin de mettre en majuscule (cf. script en bas)
				if ($maj) {
					$arrets[$aJ['codeLieu']] = strtoupper($aJ['libelle']);
				}
				//Pas de majuscule
				else {
					$arrets[$aJ['codeLieu']] = $aJ['libelle'];
				}
			}
		}

		//retourne la liste des arrêts
		return $arrets;
	}


/**
 * getArretName method
 *
 * Renvoi le nom de l'arret demandé via son idTan
 *
 * @param $idTan, $ligne, $sens
 */
	public function getArretName($idTan = null, $ligne = null, $sens = null) {
	
		//Pas de ligne et de sens donnés, on trouve l'arret parmis tout les arrets	
		if($idTan != null && ($ligne == null && $sens == null))
		{
			$arrets = $this->arretsTan();

			if($idTan != null && $arrets)
			{
				//On cherche l'idTan de cet arrêt
				$name = $arrets[$idTan];

				return $name; 
			}
		}
		//Ligne et sens donnés, on appel une page spécifique à l'arrêt pour alléger
		else if($idTan != null && $ligne != null  && $sens != null)
		{
			$json = json_decode(@file_get_contents('https://open.tan.fr/ewp/horairesarret.json/'.$idTan.'/'.$ligne.'/'.$sens, true));
			if($json)
			{
				return $arret = $json->arret->libelle;
			}
		}

		return false;

	}


/**
 * prochainPassage method
 *
 * Renvoi temps (en min) avant prochain passage à l'arrêt demandé, si le prochain passage est dans moins d'une heure
 * Sinon, renvoi 'Pas de passage avant 1 heure'.
 *
 * @param string $arret, string $ligne, int $sens, string $terminus
 * @return float|string|boolean
 */
	public function prochainPassage($arret = null, $ligne = null, $sens = null, $options = null)
	{
		$temps = null;

		if($json = json_decode(@file_get_contents('https://open.tan.fr/ewp/horairesarret.json/'.$arret.'/'.$ligne.'/'.$sens, true)))
		{

			$prochainsHoraires = $json->prochainsHoraires;

			if($prochainsHoraires)
			{
				/*
				 * On boucle sur chaque prochain passage renvoyé par l'API
				 * On calcule le delai entre maintenant et le passage (en min)
				 * Si l'option est bien celle envoyée, ou si on n'a pas demandée d'option, on retourne le delai.
				 */
				
				foreach ($prochainsHoraires as $prochain) {

					$opt = null;
					$delai = null;

					//Heure 
					
					$heure = $prochain->heure; //format 00h ou 0h

					if(strlen($heure) > 2)
						$heure = substr($heure, 0, 2); //on enlève le h pour XXh
					else
						$heure = '0'.substr($heure, 0, 1); //on enlève le h et on rajoute un 0 devant pour Xh

					//Min

					$min = $prochain->passages[0];

					if(strlen($min) > 2) { //une lettre (options) à la fin de la chaine
						$opt = substr($min, -1);
						$min = substr($min, 0, 2);
					}

					//Date
					
					$date = date("Y-m-d");
					//Si le prochain passage est le lendemain
					if($heure < date('H')) 
						date("Y-m-d", time()+86400); //Demain

					//Timestamp du prochain passage
					$timestamp = strtotime($date.' '.$heure.':'.$min.':00');

					//Si les options correspondent
					if(!$options || $options == $opt) {
						//debug($heure.':'.$min.','.$options);
						//debug(($timestamp-strtotime('now'))/60);
						
						return ($timestamp-strtotime('now'))/60;
					}

				}

			}

			return 'Pas de passage avant 1 heure au moins.';

		}

		return false;

	}

/**
 * aasort method
 * Ordonne un tableau d'arrêts selon le temps d'attente
 * J'ai pas trouvé mieux
 */
	public function aasort ($array) {

	    $newArray = array();

	    //On crée un nouveau tableau avec clé = temps d'attente
	    foreach ($array as $key => $value) {
	    	$newArray[$value['prochainPassage']] = $value;
	    }

	    //On ordonne de façon ascendante
	    ksort($newArray);

	    //On remplace le temps d'attente par un id numérique asc
	    return array_values($newArray);
	    
	}

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'arret' => array(
			'notempty' => array(
				'rule' => array('notempty'),
			),
		),
		'ligne' => array(
			'notempty' => array(
				'rule' => array('notempty'),
			),
		),
		'sens' => array(
			'numeric' => array(
				'rule' => array('numeric'),
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
	public $belongsTo = array(
		'User' => array(
			'className'  => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields'     => '',
			'order'      => ''
		),
		'Ligne' => array(
			'className'  => 'Ligne',
			'foreignKey' => 'ligne_id',
			'conditions' => '',
			'fields'     => '',
			'order'      => ''
		),
		'Lieux' => array(
			'className'  => 'Lieux',
			'foreignKey' => 'lieux_id',
			'conditions' => '',
			'fields'     => '',
			'order'      => ''
		)
	);

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Horaire' => array(
			'dependent' => true
			)
		);

}
