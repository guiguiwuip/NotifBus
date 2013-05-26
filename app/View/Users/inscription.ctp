<div class="users form">
<?php echo $this->Form->create('User'); ?>
	<fieldset>
		<legend><?php echo __('Add User'); ?></legend>
	<?php
		echo $this->Form->input('username');
		echo $this->Form->input('pass1', array(
			'label' => 'Mot de passe',
			'type'  => 'password'
		));
		echo $this->Form->input('pass2', array(
			'label' => 'Confirmation',
			'type'  => 'password'
		));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>


<div class="users form ajax">
	
	
<?php 
	echo $this->Form->create(null, array(
		'url'     => '/users/inscription',
		//'default' => false,
		'class'   => 'async',
		)); 
?>
    <?php 
		echo $this->Form->input('username');
		echo $this->Form->input('pass1', array(
			'label' => 'Mot de passe',
			'type'  => 'password'
		));
		echo $this->Form->input('pass2', array(
			'label' => 'Confirmation',
			'type'  => 'password'
		));
    ?>
    
<?php echo $this->Form->end(__('Login')); ?>



</div>
	
