<div class="arrets form">
<?php echo $this->Form->create('Arret'); ?>
	<fieldset>
		<legend><?php echo __('Add Arret'); ?></legend>
	<?php
		echo $this->Form->input('Arret.arret');
		echo $this->Form->input('Arret.ligne_id');
		echo $this->Form->input('Arret.sens', array(
			'options' => array(
					1 => 'Sens_1',
					2 => 'Sens_2'
				)
			));
		echo $this->Form->input('Arret.options');
		echo $this->Form->input('Arret.delai');

		echo $this->Form->input('Horaire.0.start');
		echo $this->Form->input('Horaire.0.end');

		echo $this->Form->input('Lieux.id', array(
			'type' => 'select'
			));		

		echo $this->Form->input('Arret.user_id');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>

<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('List Arrets'), array('action' => 'list')); ?></li>
		<li><?php echo $this->Html->link(__('List Users'), array('controller' => 'users', 'action' => 'list')); ?> </li>
		<li><?php echo $this->Html->link(__('List Horaires'), array('controller' => 'horaires', 'action' => 'list')); ?> </li>
		<li><?php echo $this->Html->link(__('List Lieux'), array('controller' => 'lieux', 'action' => 'list')); ?> </li>
	</ul>
</div>
