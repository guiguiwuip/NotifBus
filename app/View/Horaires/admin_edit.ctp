<div class="lieux form">
<?php echo $this->Form->create('Horaire'); ?>
	<fieldset>
		<legend><?php echo __('Edit Horaire'); ?></legend>
	<?php
		echo $this->Form->input('id');

		echo $this->Form->input('start');
		echo $this->Form->input('end');
		echo $this->Form->input('arret_id');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('List Users'), array('controller' => 'users', 'action' => 'list')); ?> </li>
		<li><?php echo $this->Html->link(__('List Arrets'), array('controller' => 'arrets', 'action' => 'list')); ?> </li>
		<li><?php echo $this->Html->link(__('List Horaires'), array('controller' => 'horaires', 'action' => 'list')); ?> </li>
	</ul>
</div>
