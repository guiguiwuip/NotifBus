<div class="lieux form row">
<?php 
	echo $this->Form->create('Lieux', array(
		'class' => 'span4 offset4'
	)); 
?>
	<fieldset>
		<legend>Nouveau Lieu</legend>
	<?php
		echo $this->Form->input('name');
		echo $this->Form->input('lat', array(
			'class' => 'inputLat'
			));
		echo $this->Form->input('lng', array(
			'class' => 'inputLng'
			));
		
	?>
	</fieldset>
<?php echo $this->Form->end('Enregistrer'); ?>

	<button class="setPosition btn btn-large btn-info span3 offset1">Ma position actuelle</button>


</div>
