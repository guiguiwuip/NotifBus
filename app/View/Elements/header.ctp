<?php 
	
	/* 
        Header des pages 

		@see http://twitter.github.io/bootstrap/components.html#navbar
		@see http://designmodo.github.io/Flat-UI/
	*/
?>
<header>
	<div class="navbar navbar-inverse">
	
        <div class="navbar-inner">
          <div class="container">
            <div class="nav-collapse collapse">
            
              <ul class="nav">
              
                <li class="notifMenu active"> 
                	<?php echo $this->Html->link('Notifbus', array('controller' => 'arrets', 'action' => 'index', 'admin' => false, 'prefix' => false), array('class' => 'title')); ?>
                    
                	<ul class="wrapper">
                        <li class="noNotif">
                            <a href="#">Pas de Notificiation.</a>  
                        </li>
                        <li class="onOff">
                            Notifications :
                            <div class="toggle">
                                <label class="toggle-radio" for="toggleOption4">ON</label>
                                <input type="radio" name="toggleOptions2" id="toggleOption3" value="option3"  checked="checked">
                                <label class="toggle-radio" for="toggleOption3">OFF</label>
                                <input type="radio" name="toggleOptions2" id="toggleOption4" value="option4">
                            </div>
                        </li>
                    </ul>
                    
                </li>
                
                <li> 
                    <?php echo $this->Html->link('Mon compte', array('controller' => 'users', 'action' => 'profil', 'admin' => false, 'prefix' => false)); ?> 
                    <ul>
                        <li> <?php echo $this->Html->link('Mes Lieux', array('controller' => 'lieux', 'action' => 'index', 'admin' => false, 'prefix' => false)); ?> </li>
                        <li> <?php echo $this->Html->link('Ajouter un lieu', array('controller' => 'lieux', 'action' => 'add', 'admin' => false, 'prefix' => false)); ?> </li>
                    </ul>
                </li>
                
                <li> <?php echo $this->Html->link('Déconnexion', array('controller' => 'users', 'action' => 'logout', 'admin' => false, 'prefix' => false)); ?> </li>
                
              </ul>
              
            </div>
          </div>
        </div>
        
    </div>
</header>