<?php
App::uses('AppController', 'Controller');
/**
 * Users Controller
 *
 * @property User $User
 */
class UsersController extends AppController {


/**
 * beforeFilter method
 *
 * @see AppController
 * @return void
 */
	public function beforeFilter() {
	    parent::beforeFilter();
	    $this->Auth->allow('inscription', 'login');
	}

/**
 * login method
 *
 * Connexion d'un utilisateur (AJAX ou Post classique)
 *
 * @return void
 */
	public function login() {

		$this->set('title_for_layout', 'Login');

		if($this->request->isAjax()) {

			$this->autoRender = false;

			if ($this->Auth->login()) {
				return "Connecté.";
			} else {
				return "Erreur d'identifiant ou de mot de passe.";
			}

		}
		else if ($this->request->is('post')) {

	        if ($this->Auth->login()) {
	            $this->redirect($this->Auth->redirect());
	        } else {
	            $this->Session->setFlash("Erreur d'identifiant ou de mot de passe.");
	        }
	    }
	}

/**
 * logout method
 *
 * @return void
 */
	public function logout() {
	    $this->redirect($this->Auth->logout());
	}

/**
 * inscription method
 *
 * Formulaire d'inscription, avec confirmation du mot de passe
 * Login inclu.
 * AJAX ou Post classique.
 *
 * @return void
 */
	public function inscription() {
		if ($this->request->is('post'))
		{
			$data = $this->request->data;

			if(!$this->User->check_if_exist($data['User']['username']))
			{
				if(!empty($data['User']['pass1']) && $data['User']['pass1'] == $data['User']['pass2'])
				{
					//On force le groupe utilisateur, voir admin_edit pour changer de groupe
					$data['User']['group'] = 0;
					//On enregistre le password
					$data['User']['password'] = $data['User']['pass1'];

					$this->User->create();

					if ($this->User->save($data)) 
					{
						//Login
						$this->request->data = $data;
						$this->login();

						if($this->request->isAjax()) {
							$this->autoRender = false;
							return "inscrit";
						} 

						$this->Session->setFlash("Bienvenu sur NotifBus.");
					} 
					else {
						if($this->request->isAjax()) {
							$this->autoRender = false;
							return "Il y'a une erreur dans le formulaire. Merci de réessayer.";
						}
						$this->Session->setFlash("Il y'a une erreur dans le formulaire. Merci de réessayer.");
					}
				}
				else
				{
					if($this->request->isAjax()) {
						$this->autoRender = false;
						return "Les deux mots de passe ne correspondent pas et ne peuvent pas être laissés vides.";
					} 
					$this->Session->setFlash("Les deux mots de passe ne correspondent pas et ne peuvent pas être laissés vides.");
				}
			}
			else
			{
					if($this->request->isAjax()) {
						$this->autoRender = false;
						return "Un utilisateur avec ce mot de passe existe déjà. Veuillez en utiliser un autre.";
					} 
					$this->Session->setFlash("Un utilisateur avec ce mot de passe existe déjà. Veuillez en utiliser un autre.");
			}
		}
	}

/**
 * profil method
 *
 * Profil de l'utilisateur, possibilité de changer d'identifiant
 *
 * @return void
 */
	public function profil() {
		
		if ($this->request->is('post') || $this->request->is('put')) {

			if(!$this->User->check_if_exist($this->request->data['User']['username']))
			{
				if ($this->User->save($this->request->data)) {
					$this->Session->setFlash("Vos informations ont étées mises à jour.");
					$this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash("Il y a eu une erreur. Merci de réessayer.");
				}
			}
			else 
			{
				$this->Session->setFlash("Un utilisateur avec ce mot de passe existe déjà. Veuillez en utiliser un autre.");
			}

		}

		$user = $this->User->find('first', array(
			'conditions' => array('User.id'=> $this->Auth->User()),
			'recursive' => -1
		));
		$user['User']['password'] = "";
		$this->request->data = $user;

		$this->set('user', $user);
		$this->set('admin', ($this->Auth->User('group') === "1") ? true : false);

	}

/**
 * delete method
 *
 * Si on est admin, on supprime l'utilisateur donné en paramètre
 * Sinon, on supprime le compte de l'utilisateur courant
 *
 * @throws NotFoundException
 * @throws MethodNotAllowedException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {

		$admin = ($this->Auth->User('group') === "1") ? true : false;

		//Appel par un admin
		if($admin)
		{
			//Si pas d'id donné
			if($id == null)
			{
				$this->Session->setFlash('Pas d\'utilisateur en paramètre.');
			}
			//Si l'admin veut supprimer son compte, on bloque
			else if($id == $this->Auth->User('id'))
			{
				$this->Session->setFlash('Vous ne pouvez pas supprimer votre propre compte administrateur.');
			}
		}
		//Sinon, on prend l'id de l'user courant
		else
		{
			$id = $this->Auth->User('id');
		}

		$this->User->id = $id;

		$this->request->onlyAllow('post', 'delete');

		if ($this->User->delete()) {
			$this->Session->setFlash("Compte supprimé.");
			if($admin)
				$this->redirect($this->referer());
			else
				$this->redirect($this->Auth->logout());
		}

		$this->Session->setFlash("Erreur : le compte n'a pas étét supprimé.");

		if($admin)
			$this->redirect($this->referer());
		else
			$this->redirect($this->Auth->logout());
	}


/**
 * changePassword method
 *
 * Change le password d'un utilisateur
 * 
 * Si la fonction est appellée par un administrateur, on modifie le mot de passe de l'utilisateur donnée en paramètre
 * Sinon, on change le mot de passe de l'utilisateur courant
 *
 * @param $id
 * @return void
 */
	public function changePassword($id = null){

		$admin = ($this->Auth->User('group') === "1") ? true : false;

		if($admin)
		{
			//Si pas d'id donné
			if($id == null)
			{
				$this->Session->setFlash('Pas d\'utilisateur en paramètre');
			}
			
		}
		else
		{
			$id = $this->Auth->User('id');
		}

		if($this->request->is('post') || $this->request->is('put'))
		{

			$data  = $this->request->data;
			$pass1 = $data['User']['pass1'];
			$pass2 = $data['User']['pass2'];


			if(!empty($pass1) && $pass1 == $pass2){

				$user = array(
					'User' => array(
						'id'       => $id,
						'password' => $pass1
						)
					);

				if($this->User->save($user))
				{
					$this->Session->setFlash('Votre mot de passe a bien été mis à jour.');
					$this->redirect($this->referer());
				}
				else
				{
					$this->Session->setFlash('Erreur dans la mise à jour de votre mot de passe. Merci de réessayer.');
				}
			}
			//Les deux mots de passe ne correspondent pas
			else
			{
				$this->Session->setFlash('Les deux mots de passe ne sont pas identiques. Merci de réessayer.');
			}
		}

	}



/**
 * admin_list method
 *
 * @return void
 */
	public function admin_list() {
		//$this->Users->recursive = 0;
		$this->set('users', $this->paginate());
	}

/**
 * admin_edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_edit($id = null) {

		if (!$this->User->exists($id)) {
			throw new NotFoundException(__('Invalid user'));
		}

		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash(__('The user has been saved'));
				$this->redirect(array('action' => 'list', 'admin' => true, 'prefix' => 'admin'));
			} else {
				$this->Session->setFlash(__('The user could not be saved. Please, try again.'));
			}
		} 

		//On charge l'utilisateur demandé
		else 
		{
			$user = $this->User->find('first', array(
				'conditions' => array('User.' . $this->User->primaryKey => $id),
				'recursive' => -1
			));
			$user['User']['password'] = "";

			$this->request->data = $user;	
			$this->set('user', $user);
		}
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {

		if ($this->request->is('post')) {
			$this->User->create();
			
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash(__('The user has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The user could not be saved. Please, try again.'));
			}
		}
	}



}
