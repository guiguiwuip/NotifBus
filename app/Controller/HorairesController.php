<?php
App::uses('AppController', 'Controller');
/**
 * Horaires Controller
 *
 */
class HorairesController extends AppController {


/**
 * horaires method
 *
 * Renvoi la liste des créneaux horaires d'un arret
 * 
 * @param  int $arret 
 * @return void
 */
	public function horaires($arret = null) {

		$this->layout = false;

		$json = false;

		if($arret) {

			$horaires = $this->Horaire->find('all', array(
				'conditions' => array('Arret.id' => $arret)
				));

			if($horaires) {
				$json = array()
				foreach($horaires as $h) {
					$json[] = array(
						'id'    => $h['Horaire']['id'],
						'start' => $h['Horaire']['start'],
						'end'   => $h['Horaire']['end'],
					);
				}
			}
		}

		$this->set('horaires', json_encode($json));
	}



/**
 * admin_list method
 *
 * @return void
 */
	public function admin_list() {
		$this->Horaire->recursive = 0;
		$this->set('horaires', $this->paginate());
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->Horaire->create();
			if ($this->Horaire->save($this->request->data)) {
				$this->Session->setFlash(__('The Horaire has been saved'));
				$this->redirect(array('action' => 'list'));
			} else {
				$this->Session->setFlash(__('The Horaire could not be saved. Please, try again.'));
			}
		}

		$arrets = $this->Horaire->Arret->find('list');
		$this->set(compact('arrets'));
	}

/**
 * admin_edit method
 *
 * @param string $id
 * @return void
 */
	public function admin_edit($id = null) {

		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Horaire->save($this->request->data)) {
				$this->Session->setFlash(__('The Horaire has been saved'));
				$this->redirect(array('action' => 'list'));
			} else {
				$this->Session->setFlash(__('The Horaire could not be saved. Please, try again.'));
			}
		}

		$this->request->data = $this->Horaire->find('first', array(
			'conditions' => array('Horaire.id' => $id)
			));

		$arrets = $this->Horaire->Arret->find('list');
		$this->set(compact('arrets'));
	}
		
/**
 * admin_delete method
 *
 * @throws MethodNotAllowedException
 * @param string $id
 * @return void
 */
	public function admin_delete($id = null) {
		$this->Horaire->id = $id;
		
		$this->request->onlyAllow('post', 'delete');

		if ($this->Horaire->delete()) {
			$this->Session->setFlash(__('Horaire deleted'));
			$this->redirect(array('action' => 'list'));
		}

		$this->Session->setFlash(__('Horaire was not deleted'));
		$this->redirect(array('action' => 'list'));
	}
}
