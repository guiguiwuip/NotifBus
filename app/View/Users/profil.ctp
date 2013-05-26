<div class="users form row">

<?php 
	echo $this->Form->create('User', array(
		'class' => 'span5  offset1'
	)); 
?>
	<fieldset>
		<legend>Modifier mes informations</legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('username', array(
			'label'       => array(
				'text'  => ''
			),
			'class'       => 'login-field',
			'placeholder' => 'Pseudo',
			'div'         => 'control-group'
		));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>

<?php 
	echo $this->Form->create('User', array(
		'action' => 'changePassword',
		'class'  => 'span5  offset1'
	)); 
?>
	<fieldset>
		<legend>Modifier mon mot de passe</legend>
	<?php
		echo $this->Form->input('pass1', array(
    		'label'       => array(
    			'text'  => ''
    		),
    		'class'       => 'login-field',
    		'placeholder' => 'Mot de passe',
    		'div'         => 'control-group'
    	));
        echo $this->Form->input('pass2', array(
    		'label'       => array(
    			'text'  => ''
    		),
    		'class'       => 'login-field',
    		'placeholder' => 'Confirmation',
    		'div'         => 'control-group'
    	));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>

<?php 
	if(!$admin)
	{
		echo $this->Form->postLink(
			"Supprimer mon compte", 
			array('action' => 'delete', 'params' => $user['User']['id']), 
			array('class' => 'btn btn-large btn-danger'), 
			__('En supprimant votre compte, vous perdez tous vos arrêts favoris et paramètres. Continuer ?')
		);
	}
?>


</div>