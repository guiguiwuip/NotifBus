<div class="lieux form">
<?php echo $this->Form->create('Lieux'); ?>
	<fieldset>
		<legend><?php echo __('Edit Lieu'); ?></legend>
	<?php
		echo $this->Form->input('id');

		echo $this->Form->input('name');
		echo $this->Form->input('lat');
		echo $this->Form->input('lng');
		
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>

