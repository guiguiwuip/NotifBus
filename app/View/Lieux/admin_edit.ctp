<div class="lieux form">
<?php echo $this->Form->create('Lieux'); ?>
	<fieldset>
		<legend><?php echo __('Edit Lieu'); ?></legend>
	<?php
		echo $this->Form->input('id');

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

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Lieux.id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('Lieux.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Lieuxes'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Arrets'), array('controller' => 'arrets', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Arret'), array('controller' => 'arrets', 'action' => 'add')); ?> </li>
	</ul>
</div>
