<?php
App::uses('AppController', 'Controller');
/**
 * Lieux Controller
 *
 * @property Lieux $Lieux
 */
class LieuxController extends AppController {

/**
 * beforeFilter method
 *
 * @see AppController
 * @return void
 */
	public function beforeFilter() {
	    parent::beforeFilter();
	}


/**
 * index method
 *
 * @return void
 */
	public function index() {
		$lieux = $this->Lieux->find('all', array(
			'conditions' => array('Lieux.user_id' => $this->Auth->User('id')),
			'recursive' => -1
			));
		$this->set('lieux', $this->paginate('Lieux', array('Lieux.user_id' => $this->Auth->User('id'))));
	}


/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			
			$this->request->data['Lieux']['user_id'] = $this->Auth->User('id');
			$this->Lieux->create();

			if ($this->Lieux->save($this->request->data)) {
				$this->Session->setFlash('Le lieu a été enregistré.');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash("Une erreur s'est produite. Merci de réessayer.");
			}
		}
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		
		if ($this->request->is('post') || $this->request->is('put')) {

			$this->request->data['Lieux']['user_id'] = $this->Auth->User('id');

			if ($this->Lieux->save($this->request->data)) {
				$this->Session->setFlash('Le lieu a été enregistré.');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash("Une erreur s'est produite. Merci de réessayer.");
			}
		} 

		$this->request->data = $this->Lieux->find('first', array(
			'conditions' => array('Lieux.id' => $id, 'Lieux.user_id' => $this->Auth->User('id'))
			));
		
	}

/**
 * delete method
 *
 * @throws MethodNotAllowedException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {

		$lieu = $this->Lieux->find('first', array(
			'conditions' => array('Lieux.id' => $id, 'Lieux.user_id' => $this->Auth->User('id'))
			));

		if($lieu)
		{
			$this->Lieux->id = $id;
			
			$this->request->onlyAllow('post', 'delete');
			if ($this->Lieux->delete()) {
				$this->Session->setFlash("Lieu supprimé");
				$this->redirect(array('action' => 'index'));
			}
			else
			{
				$this->Session->setFlash("Une erreur s'est produite. Merci de réessayer.");
			}
		}

		$this->Session->setFlash("Vous ne pouvez pas supprimer ce lieu.");
		$this->redirect(array('action' => 'index'));
	}



/**
 * lieux method
 *
 * Renvoi les lieux d'un utilisateur
 * 
 * @param  int $arret 
 * @return void
 */
	public function lieux() {

		$this->layout = false;

		$json = false;

		$lieux = $this->Lieux->find('all');

		if($lieux) {
			$json = array();
			foreach($lieux as $l) {
				$json[] = array(
					'id'    => $l['Lieux']['id'],
					'name'  => $l['Lieux']['name']
				);
			}
		}

		$this->set('lieux', json_encode($json));
	}




/**
 * admin_list method
 *
 * @return void
 */
	public function admin_list() {
		$this->Lieux->recursive = 0;
		$this->set('lieux', $this->paginate());
	}


/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->Lieux->create();
			if ($this->Lieux->save($this->request->data)) {
				$this->Session->setFlash(__('The lieux has been saved'));
				$this->redirect(array('action' => 'list'));
			} else {
				$this->Session->setFlash(__('The lieux could not be saved. Please, try again.'));
			}
		}
		$users = $this->Lieux->User->find('list');
		$this->set(compact('users'));
	}

/**
 * admin_edit method
 *
 * @param string $id
 * @return void
 */
	public function admin_edit($id = null) {

		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Lieux->save($this->request->data)) {
				$this->Session->setFlash(__('The lieux has been saved'));
				$this->redirect(array('action' => 'list'));
			} else {
				$this->Session->setFlash(__('The lieux could not be saved. Please, try again.'));
			}
		}

		$this->request->data = $this->Lieux->find('first', array(
			'conditions' => array('Lieux.id' => $id)
			));

		
		$users = $this->Lieux->User->find('list');
		$this->set(compact('users'));
	}

/**
 * admin_delete method
 *
 * @throws MethodNotAllowedException
 * @param string $id
 * @return void
 */
	public function admin_delete($id = null) {
		$this->Lieux->id = $id;
		
		$this->request->onlyAllow('post', 'delete');

		if ($this->Lieux->delete()) {
			$this->Session->setFlash(__('Lieux deleted'));
			$this->redirect(array('action' => 'list'));
		}

		$this->Session->setFlash(__('Lieux was not deleted'));
		$this->redirect(array('action' => 'list'));
	}

}
