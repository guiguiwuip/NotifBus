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

		echo $this->Form->input('lieux_id', array(
			'options' => $lieux
		));		

	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
