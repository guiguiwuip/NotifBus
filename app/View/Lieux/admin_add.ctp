<div class="lieux form">
<?php echo $this->Form->create('Lieux'); ?>
	<fieldset>
		<legend><?php echo __('Add Lieu'); ?></legend>
	<?php
		echo $this->Form->input('name');
		echo $this->Form->input('lat');
		echo $this->Form->input('lng');
		
		echo $this->Form->input('user_id');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>

<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('New Lieux'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Users'), array('controller' => 'users', 'action' => 'list')); ?> </li>
		<li><?php echo $this->Html->link(__('List Arrets'), array('controller' => 'arrets', 'action' => 'list')); ?> </li>
		<li><?php echo $this->Html->link(__('List Horaires'), array('controller' => 'horaires', 'action' => 'list')); ?> </li>
	</ul>
</div>