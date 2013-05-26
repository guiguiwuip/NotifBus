<?php
App::uses('AppController', 'Controller');
/**
 * Arrets Controller
 *
 * @property Arret $Arret
 */
class ArretsController extends AppController {


/**
 * beforeFilter method
 *
 * @see AppController
 * @return void
 */
	public function beforeFilter() {
	    parent::beforeFilter();

	   	$this->Auth->allow('index');

	}



/**
 * index method
 *
 * Page d'accueil de l'application.
 * Ne fait rien d'autres que charger l'html et le js
 *
 * @return void
 */
	public function index() {

		$this->set('title_for_layout', '');

		//Si l'utilisateur est connecté
		if($this->Auth->User())
		{
			$this->layout = 'home';
			if($this->request->isAjax()){
				$this->layout = false;
			} 

		}
		//Utilisateur non connecté
		else
		{
			$this->layout = 'login';
		}

	}

/**
 * update method
 *
 * Liste tous les arrêt favoris de l'utilisateur ordonnés en fonction du temps d'attente avant prochain passage.
 * Envoi les notifications de passage.
 * 
 * A FAIRE : retourne les arrêt en json, jQuery se charge de l'update de la page.
 * A FAIRE : retourne les notification si il y en a
 *
 * @param float $lat, float $lng
 * @return void
 */
	public function update($lat = null, $lng = null){

		$this->layout = 'ajax';

		$notifications = array();
		$arrets = $this->Arret->find('all', array(
			'conditions' => array('User.id' => $this->Auth->User('id'))
			));

		//On boucle sur chaque arrêt
		for ($i=0; $i < count($arrets); $i++) 
		{
			/*
			 * Gestion du prochain passage à l'arrêt
			 *
			 * On sort le nom de l'arrêt, le temps d'attente et le terminus option si il y en a
			 */
			
			$arret = $arrets[$i];

			//Temps d'attente
			$attente = $this->Arret->prochainPassage(
				$arret['Arret']['arret'],
				$arret['Ligne']['idTan'],
				$arret['Arret']['sens'],
				$arret['Arret']['options']
			);

			//Nom de l'arrêt
			$name = $this->Arret->getArretName(
				$arret['Arret']['arret'],
				$arret['Ligne']['idTan'],
				$arret['Arret']['sens']
			);

			//Terminus de la ligne
			$this->loadModel('Terminus');
			$terminus = $this->Terminus->find('first', array(
				'conditions' => array(
					'ligne_id' => $arret['Ligne']['id'],
					'idTan'    => ($arret['Arret']['options'] == 0) ? '' : $arret['Arret']['options'],
					'sens'     => $arret['Arret']['sens']
					),
				'recursive' => -1
				));
			
			$arrets[$i]['prochainPassage']   = (is_numeric($attente)) ? (int)$attente : $attente;
			$arrets[$i]['sens']              = $arret['Ligne']['sens_'.$arret['Arret']['sens']].'.';
			if(!empty($terminus['Terminus']['idTan']))
				$arrets[$i]['sens'] = $arret['Ligne']['sens_'.$arret['Arret']['sens']].', '.$terminus['Terminus']['name'];
			$arrets[$i]['Arret']['name']     = $name;
			$arrets[$i]['Ligne']['Terminus'] = ($terminus) ? $terminus['Terminus'] : '';

			$arret = $arrets[$i];

			/*
			 * Gestion des notifications :
			 *
			 * Envoi si on se trouve dans une plage horaire
			 * Envoi si on se trouve à 500m du lieu paramètré (si il y en a)
			 */

			//Si on est dans le delai de notification
			if(is_numeric($attente) && $attente <= $arret['Arret']['delai'])
			{

				$plageHoraireOk = false;
				$lieuOk = false;

				//On check si on est dans une plage horaire de notif
				if(!empty($arret['Horaire']))
				{
					foreach ($arret['Horaire'] as $key => $horaire) {

						// debug(strtotime($horaire['start']));
						// debug(strtotime("now"));
						// debug(strtotime($horaire['end']));

						if(strtotime($horaire['start']) <= strtotime("now")
							&& strtotime($horaire['end']) >= strtotime("now")
							&& $horaire['nePlusMeRappeler'] != date("Y-m-d")." 00:00:00")
						{
							$plageHoraireOk = true;
						}
					}
				}
				else
				{
					//Pas de plage horaire, notif tout le temps
					$plageHoraireOk = true;
				}


				//Si on est dans une plage horaire
				if($plageHoraireOk)
				{
					//On check le lieu
					if($arret['Lieux']['id'] != null && ($lat != null && $lng != null)) 
					{
						// Si un lieu a été précisé pour l'arrêt (par user) et pour la requête (captage et envoi par jQuery)

						//Librairie Geocode
						App::uses('GeocodeLib', 'Tools.Lib');
						$this->Geocode = new GeocodeLib();

						//On calcule la distance entre le lieu de l'arrêt et la position de l'utilisateur
						$distance = $this->Geocode->distance(
							array('lat'=> $lat, 'lng'=> $lng),
							array('lat' => $arret['Lieux']['lat'], 'lng' => $arret['Lieux']['lng']),
							'F' //en pieds
						);
						
						$distance *= 0.3048; // en mètres
						//debug($distance);

						//Si l'utilisateur se trouve dans un rayon de 500m autour du lieu paramètré, on considère qu'il y est.
						if($distance < 500)
							$lieuOk = true;

					}
					else
					{
						//Pas de lieu précisé, notif 
						$lieuOk = true;
					}

					if($lieuOk)
					{
						//Arrivée ici = on est dans la plage horaire et le lieu est le bon = on peut envoyer la notif !
						
						$notifications[] = array(
							'Arret' => array(
								'id'   => $arret['Arret']['id'],
								'name' => $name,
								),
							'Ligne' => array(
								'id'   => $arret['Ligne']['idTan'],
								'name' => $arret['Ligne']['name'],
								'sens' => $arret['Ligne']['sens_'.$arret['Arret']['sens']],
								'options' => 
									($arret['Ligne']['Terminus']['name'] != $arret['Ligne']['sens_'.$arret['Arret']['sens']]) ? $arret['Ligne']['Terminus']['name'] : null
								),
							'attente' => $arret['prochainPassage']
						);

					}

				}

			}

		}

		/*
		  On range les arrets en fonction du temps de passage 
		 */
		$arrets = $this->Arret->aasort($arrets, "prochainPassage");
	
		//Envoi à la vue
		$this->set('arrets', $arrets);
		$this->set('notifications', $notifications);

	}

/**
 * nePlusMeRappeler method
 *
 * Enregistre dans l'horaire courant de l'arret demandé la date du jour, 
 * ce qui empèche l'envoi d'une notif de cet arrêt pour cet horaire
 * 
 * @param  int $id (id de l'arret à mettre à jour)
 * @return void
 */
public function nePlusMeRappeler($id) {

	$this->layout = 'ajax';

	$arret = $this->Arret->find('first', array(
		'conditions' => array(
			'Arret.user_id'  => $this->Auth->User('id'),
			'Arret.id' => $id
			)
		));

	//Si l'arret existe bien et appartient bien à l'utilisateur
	if($arret) {

		$horaires = $arret['Horaire'];

		for ($i = 0; $i < count($arret['Horaire']); $i++) {

			// debug(strtotime($horaire['start']));
			// debug(strtotime("now"));
			// debug(strtotime($horaire['end']));

			if(strtotime($horaires[$i]['start']) <= strtotime("now")
				&& strtotime($horaires[$i]['end']) >= strtotime("now"))
			{
				//On est dans cette plage horaire actuellement 
				//On stocke la date du jour dans l'horaire
				$horaires[$i]['nePlusMeRappeler'] = date("Y-m-d");
			}
		}

		if($this->Arret->Horaire->saveMany($horaires)) {
			return 'ok';
		}

	}

}


/**
 * add method
 *
 * Création d'un nouvel arrêt favoris, avec horaires et lieux, par un utilisateur
 *
 * @return void
 */
	public function add() {

		if ($this->request->is('post')) 
		{	
			$data = $this->request->data;

			$data['Arret']['user_id'] = $this->Auth->User('id');

			if ($this->Arret->saveAssociated($data)) {
				$this->Session->setFlash('Nouvel arrêt enregistré !');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash('Il y a eu une erreur dans l\'enregistrement. Merci de réessayer.');
			}
		}

		$lignes = $this->Arret->Ligne->find('list');
		$this->set(compact('lignes'));

		$lieux = $this->Arret->Lieux->find('list', array(
			'conditions' => array('Lieux.user_id' => $this->Auth->User('id'))
			));
		$lieux = array(0 => 'Non') + $lieux;

		$this->set('lieux', $lieux);

	}

/**
 * edit method
 *
 * Edition ou création d'un arrêt favoris par son propriétaire
 * Appelé par Ajax.
 * 
 * @param int $id (si non renseigné, on est en création ajax)
 * @return void
 */
	public function edit($id = null) {

		$ajax = false;

		//Si Ajax, on désactive le layout
		if($this->request->isAjax()) {
			$this->layout = false;
			$ajax = true;
		}

		/*
		 * Si on a posté les données
		 */
		if ($this->request->is('post') || $this->request->is('put')) {

			/*
			 * Si un id est donné, on est en édition
			 */
			$arret = false;
			if($id)
				$arret = $this->Arret->find('first', array(
					'conditions' => array(
						'Arret.id' => $id, 
						'Arret.user_id' => $this->Auth->User('id')
					)
				));

			//Si l'arret existe et appartient bien à l'utilisateur ou si on crée un nouvel arrêt
			if($arret || $id == null)
			{
				//On sécurise l'appartenance
				$this->request->data['User']['id'] = $this->Auth->User('id');

				//On sauvegarde
				if ($this->Arret->saveAll($this->request->data, array('deep' => true))) {

					//On est en ajax, on renvoie une confirmation
					if($ajax) {
						$this->autoRender = false;
						return "Arrêt sauvegardé.";
					}

					//Sinon on redirige sur l'accueil
					$this->Session->setFlash("Votre arrêt a bien été sauvegardé.");
					$this->redirect(array('action' => 'index'));

				} 
				//Erreur de sauvegarde
				else {

					//On est en ajax, on renvoie un message d'erreur
					if($ajax) {
						$this->autoRender = false;
						return "Erreur dans la sauvegarde.";
					}

					$this->Session->setFlash("Une erreur s'est produite. Merci de réessayer.");
				}
			}
			else
			{
				//On est en ajax, on renvoie un message d'erreur
				if($ajax) {
					return "Erreur dans la sauvegarde.";
				}

				$this->Session->setFlash("Vous ne pouvez pas modifier cet arrêt.");
			}

		}

		/*
		 * Préparation de la vue
		 */

		//Si on est en ajax, on a besoin de préremplir le select avec tous les arrêts
		if($this->request->isAjax()) {
			
			//Tous les Arrets
			$arrets = $this->Arret->arretsTan();
			$this->set('arretsListe', $arrets);
		} 
		

		//Si il ne s'agit pas d'une création
		if($id) 
		{
			//On charge l'arrêt demandé pour le pré-remplissage du formulaire
			$this->request->data = $this->Arret->find('first', array(
				'conditions' => array(
					'Arret.id' => $id, 
					'Arret.user_id' => $this->Auth->User('id'))
			));

			//On charge les terminus spéciaux de la ligne de l'arrêt
			$this->loadModel('Terminus');
			$this->request->data['Ligne']['Terminus'] = $this->Terminus->find('list', array(
				'fields' => array('Terminus.idTan', 'Terminus.name'),
				'conditions' => array(
					'Terminus.ligne_id' => $this->request->data['Ligne']['id'],
					'Terminus.idTan !=' => ''
					),
				'recursive'  => -1
			));

			if($this->request->isAjax()) {
				//Les lignes passant à l'arrêt
				$lignes = $this->Arret->Ligne->lignes($this->request->data['Arret']['arret']);
			} else {
				//Toutes les lignes
				$lignes = $this->Arret->Ligne->find('list');
			}
		}

		$this->set(compact('lignes'));

		//Tous les lieux de l'utilisateur
		$lieux = $this->Arret->Lieux->find('list', array(
			'conditions' => array('Lieux.user_id' => $this->Auth->User('id'))
			));
		$lieux = array(0 => 'Non') + $lieux;
		$this->set('lieux', $lieux);

		
		//Ajax
		$this->set('ajax', $ajax);
	}

/**
 * delete method
 * 
 * Suppression d'un arrêt (avec horaires et lieux correspondant) par son propriétaire
 * On vérifie si l'arrêt demandé appartient bien à l'utilisateur courant
 * 
 * @param string $id
 * @return void
 */
	public function delete($id = null) {

		$arret = $this->Arret->find('first', array(
			'conditions' => array('Arret.id' => $id)
			));

		//Si l'arret existe et appartient bien à l'utilisateur
		if($arret && $arret['Arret']['user_id'] == $this->Auth->User('id'))
		{
			$this->Arret->id = $id;
			
			//$this->request->onlyAllow('post', 'delete');

			if ($id != null && $this->Arret->delete()) {
				$this->Session->setFlash("Arret supprimé.");
				$this->redirect(array('action' => 'index'));
			}

			$this->Session->setFlash("Une erreur s'est produite. Merci de réessayer.");
			$this->redirect(array('action' => 'index'));
		}
		else
		{
			$this->Session->setFlash("Vous ne pouvez pas supprimer cet arrêt.");
		}
	}

/**
 * admin_list method
 *
 * Liste tous les arrêts
 * 
 * @return void
 */
	public function admin_list() {
		$this->set('arrets', $this->paginate());
	}

/**
 * admin_add method
 *
 * Création d'un nouvel arrêt favoris, avec horaires et lieux, par un admin
 *
 * @return void
 */
	public function admin_add() {

		if ($this->request->is('post')) 
		{	
			if ($this->Arret->saveAssociated($this->request->data)) {
				$this->Session->setFlash(__('The arret has been saved'));
				$this->redirect(array('action' => 'list'));
			} else {
				$this->Session->setFlash(__('The arret could not be saved. Please, try again.'));
			}
		}

		$users = $this->Arret->User->find('list');
		$this->set(compact('users'));

		$lignes = $this->Arret->Ligne->find('list');
		$this->set(compact('lignes'));

		$lieux = $this->Arret->Lieux->find('list');
		$this->set(compact('lieux'));
	}

/**
 * admin_edit method
 *
 * Edition d'un arrêt favoris par un admin
 *
 * @param string $id
 * @return void
 */
	public function admin_edit($id = null) {
		
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Arret->saveAssociated($this->request->data)) {
				$this->Session->setFlash(__('The arret has been saved'));
				$this->redirect(array('action' => 'list'));
			} else {
				$this->Session->setFlash(__('The arret could not be saved. Please, try again.'));

			}
		} 

		$this->request->data = $this->Arret->find('first', array(
			'conditions' => array('Arret.id' => $id)
			));

		$users = $this->Arret->User->find('list');
		$this->set(compact('users'));

		$lignes = $this->Arret->Ligne->find('list');
		$this->set(compact('lignes'));

		$lieux = $this->Arret->Lieux->find('list');
		$this->set(compact('lieux'));
	}

/**
 * admin_delete method
 * 
 * Suppression d'un arrêt (avec horaires et lieux correspondant) par un admin
 * 
 * @param string $id
 * @return void
 */
	public function admin_delete($id = null) {

		$this->Arret->id = $id;
		
		$this->request->onlyAllow('post', 'delete');

		if ($id != null && $this->Arret->delete()) {
			$this->Session->setFlash(__('Arret deleted'));
			$this->redirect(array('action' => 'list'));
		}

		$this->Session->setFlash(__('Arret was not deleted'));
		$this->redirect(array('action' => 'list'));
	}


}
