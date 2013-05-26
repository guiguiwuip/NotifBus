<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {


/**
 * Ajout des components pour tous les controllers
 * @var array
 */
    //public $components = array('Session', 'Auth', 'DebugKit.Toolbar');
    public $components = array('Session', 'Auth');
   // public $theme = "Cakestrap";

    
/**
 * beforFilter method
 *
 * Executed before every action in the controller
 *
 * @return void
 */
    public function beforeFilter() {

    	//Config Auth
		$this->Auth->deny();
		$this->Auth->logoutRedirect = array('controller' => 'arrets', 'action' => 'index', 'admin' => false, 'prefix' => null);
		$this->Auth->loginRedirect  = array('controller' => 'arrets', 'action' => 'index', 'admin' => false, 'prefix' => null);
		$this->Auth->loginAction    = '/';
		$this->Auth->authError      = 'Vous devez être connecté pour accèder à cette partie du site.';


		//Si on demande une page admin
	    if(isset($this->request->params['admin'])){

	    	//Si l'utilisateur n'est admin, on ne renvoi à la page d'accueil
	    	if($this->Auth->user('group') !== "1"){
	    		$this->Session->setFlash("Vous ne pouvez pas accéder à la page demandée.");
	    		$this->redirect($this->Auth->redirect());
	    	}

	    }

    }

/**
 * beforRender method
 *
 * Executed before every render
 *
 * @return void
 */
    public function beforeRender(){

    	//On ovewrite le flash par defaut pour changer son template
	    if ($this->Session->check('Message.flash')) {
	        $flash = $this->Session->read('Message.flash');

	        if ($flash['element'] == 'default') {
	            $flash['element'] = 'flash_default';
	            $this->Session->write('Message.flash', $flash);
	        }
	    }
	}

}
