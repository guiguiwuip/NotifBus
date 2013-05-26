		<div class="login-screen inline">

			<div class="inscription login-form inline">
			<?php 
				echo $this->Form->create('User', array(
					'action'  => 'inscription',
					'url'     => '/users/inscription',
					'class'   => 'async',
				)); 
			?>
			<?php
		    	echo $this->Form->input('username', array(
		    		'label'       => array(
		    			'class' => "login-field-icon fui-man-16",
		    			'text'  => ''
		    		),
		    		'class'       => 'login-field',
		    		'placeholder' => 'Pseudo',
		    		'div'         => 'control-group'
		    		));
		        echo $this->Form->input('pass1', array(
		    		'label'       => array(
		    			'class' => "login-field-icon fui-lock-16",
		    			'text'  => ''
		    		),
		    		'class'       => 'login-field',
		    		'type'        => 'password',
		    		'placeholder' => 'Mot de passe',
		    		'div'         => 'control-group'
		    		));
		        echo $this->Form->input('pass2', array(
		    		'label'       => array(
		    			'class' => "login-field-icon fui-lock-16",
		    			'text'  => ''
		    		),
		    		'class'       => 'login-field',
		    		'type'        => 'password',
		    		'placeholder' => 'Confirmation',
		    		'div'         => 'control-group'
		    		));
			
				echo $this->Form->end('S\'inscire');
			?>
			</div>

			<div class="login-icon inline">
				<img src="images/login/icon.png" alt="Welcome to Mail App" />
				<h4>Welcome to <small>NotifBus</small></h4>
			</div>

			<div class="connexion login-form inline">
			<?php 
				echo $this->Form->create('User', array(
					'action'  => 'login',
					'url'     => '/users/login',
					'class'   => 'async',
				)); 
			?>
			<?php
		    	echo $this->Form->input('username', array(
		    		'label'       => array(
		    			'class' => "login-field-icon fui-man-16",
		    			'text'  => ''
		    		),
		    		'class'       => 'login-field',
		    		'placeholder' => 'Pseudo',
		    		'div'         => 'control-group'
		    		));
		        echo $this->Form->input('password', array(
		    		'label'       => array(
		    			'class' => "login-field-icon fui-lock-16",
		    			'text'  => ''
		    		),
		    		'class'       => 'login-field',
		    		'placeholder' => 'Mot de passe',
		    		'div'         => 'control-group'
		    		));

			
				echo $this->Form->end('Se connecter');
			?>
			</div>

		</div>
		<div class="etai"></div>
