<div class="users form">
<?php echo $this->Form->create('User'); ?>

	<fieldset>
		<legend><?php echo __('Edit User'); ?></legend>
		
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('username');
		
		echo $this->Form->input('group', array(
			'options' => array(
					0 => 'Utilisateur',
					1 => 'Administrateur'
				)
			));
	?>	
	</fieldset>
	
<?php echo $this->Form->end(__('Submit')); ?>

	<hr>

<?php 
	echo $this->Form->create('User', array(
		'url'    => '/users/changePassword/' . $user['User']['id'],
		'params' => $user['User']['id'],
		)
	); 
?>
	<fieldset>
		<legend><?php echo __('Change Password :'); ?></legend>
			
		<?php
			echo $this->Form->input('pass1', array(
				'label' => 'Password',
				'type'  => 'password'
			));
			echo $this->Form->input('pass2', array(
				'label' => 'Confirm',
				'type'  => 'password'
			));
		?>
	</fieldset>

<?php echo $this->Form->end(__('Submit')); ?>

</div>

<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('User.id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('User.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Users'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Arrets'), array('controller' => 'arrets', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Arret'), array('controller' => 'arrets', 'action' => 'add')); ?> </li>
	</ul>
</div>
