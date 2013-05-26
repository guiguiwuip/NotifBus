<?php 
	//Templates différents entre ajax et non-ajax
	if($ajax) : 
?>

	<div class="edit navbar navbar-inverse">
		<div class="navbar-inner">
			<div class="nav-collapse collapse">
				<div class="container">
			<?php
				echo $this->Form->create('Arret');
				echo $this->Form->input('id');

					//Si on est en édition, on pré-remplit les champs 
					if(isset($this->data['Arret']['id'])) :
			?>			
					<ul class="nav arret">
					
						<li class="inline">
							<?php 
								//Nom de l'arret, préremplit avec tous les arrets
								echo $this->Form->input('Arret.arret', array(
									'options'     => $arretsListe,
									'label'       => false,
									'placeholder' => 'Arret'
								)); 
							?>
						</li>
						<li class="inline">
							<?php 
								//Id de la ligne, préremplit avec toutes les lignes passant à l'arrêt 
								echo $this->Form->input('Arret.ligne_id', array(
									'options' => $lignes,
									'label'   => false,
								)); 
							?>
						</li>
						<li class="inline">
							<?php 
								//Sens 
								echo $this->Form->input('Arret.sens', array(
									'options' => array(
										1 => 'Vers '.$this->data['Ligne']['sens_1'],
										2 => 'Vers '.$this->data['Ligne']['sens_2']
										),
									'label' => false
								)); 
							?>
						</li>
						<li class="inline">
							<?php 
								//Options terminu
								echo $this->Form->input('Arret.options', array(
									'options' => array('' => 'Pas d\'options')+$this->data['Ligne']['Terminus'],
									'label'   => false
								)); 
							?>
						</li>
						
					</ul>
					
					<?php 
						//On boucle sur chaque créneau horaire
						foreach ($this->data['Horaire'] as $key => $horaire):
					?>
										
						<ul class="nav horaires" data-nb="<?php echo $key; ?>">
							<?php echo $this->Form->input('Horaire.'.$key.'.id'); ?>
							
							<li class="inline">
								<?php echo $this->Form->input('Horaire.'.$key.'.start', array(
									'timeFormat' => '24',
									'label'      => false
								)); ?>
							</li>
							<li class="inline">
								<?php echo $this->Form->input('Horaire.'.$key.'.end', array(
									'timeFormat' => '24',
									'label'      => false
								)); ?>
							</li>
							<li class="inline"><span class="fui-plus-24"></span></li>
							
						</ul>
					
					<?php endforeach; ?>
					
					<ul class="nav end">
						<li class="inline">
							<?php 
								//Delai avant notif
								echo $this->Form->input('Arret.delai', array(
									'label' => "Delai : ",
									'default' => '5',
								));	
							?>
						</li>
						<li class="inline">
							<?php 
								//Les lieux de l'utilisateur
								echo $this->Form->input('Arret.lieux_id', array(
									'options' => $lieux,
									'label'   => 'Lieux : '
								));	
							?>
						</li>
						<li class="inline"><?php echo $this->Form->end('Enregistrer'); ?></li>
					</ul>
					
					<ul class="actions nav">
						<li class="inline">
							<?php 
								echo $this->Form->postLink(
									'Supprimer',
									'/arrets/delete/'.$this->data['Arret']['id'],
									null,
									'Êtes-vous sur de vouloir supprimer cet arrêt ?'
								); 
							?>
						</li>
						<li class="inline"><span class="fui-cross-24"></span></li>
					</ul>
					
				<?php 
					//On est en création
					else : 
				?>
					<ul class="nav arret">
					
						<li class="inline">
						<?php 
							echo $this->Form->input('Arret.arret', array(
								'options' => $arretsListe,
								'label'   => false,
								'placeholder' => 'Arret'
							));
						?>
						</li>
						<li class="inline">
						<?php 
							echo $this->Form->input('ligne_id', array(
								'options' => false,
								'label'   => false
							));
						?>
						</li>
						<li class="inline">
						<?php 
							echo $this->Form->input('Arret.sens', array(
								'options' => false,
								'label'   => false
							));
						?>
						</li>
						<li class="inline">
						<?php 
							echo $this->Form->input('Arret.options', array(
								'options' => false,
								'label'   => false
							));
						?>
						</li>
						
					</ul>
					
										
					<ul class="nav horaires" data-nb="0">
						<?php echo $this->Form->input('Horaire.0.id'); ?>
						
						<li class="inline">
							<?php 
								echo $this->Form->input('Horaire.0.start', array(
									'timeFormat' => '24',
									'label'      => false
								)); 
							?>
						</li>
						<li class="inline">
							<?php 
								echo $this->Form->input('Horaire.0.end', array(
									'timeFormat' => '24',
									'label'      => false
								)); 
							?>
						</li>
						<li class="inline"><span class="fui-plus-24"></span></li>
						
					</ul>
					
					
					<ul class="nav end">
						<li class="inline">
							<?php 
								echo $this->Form->input('Arret.delai', array(
									'label' => "Delai : ",
									'value' => '5',
								));	
							?>
						</li>
						<li class="inline">
							<?php 
								echo $this->Form->input('Arret.lieux_id', array(
									'options' => $lieux,
									'label'   => 'Lieux : '
								));	
							?>
						</li>
						<li class="inline">
						<?php echo $this->Form->end('Enregistrer'); ?></li>
					</ul>
					
					<ul class="actions nav">
						<li class="inline"><span class="fui-cross-24"></span></li>
					</ul>

					<?php
						endif;
					?>
					
				</div>
			</div>
		</div>
	</div>	
	
<?php 
	//On est pas en ajax, formulaire classique
	else : 
?>

	<div class="arrets form">
	
	<?php echo $this->Form->create('Arret'); ?>
		<fieldset>
			<legend><?php echo __('Edit Arret'); ?></legend>
		<?php

			echo $this->Form->input('id');
			echo $this->Form->input('arret');
			echo $this->Form->input('ligne_id');
			echo $this->Form->input('sens');
			echo $this->Form->input('options');
			echo $this->Form->input('delai');

			echo $this->Form->input('Horaire.0.id');
			echo $this->Form->input('Horaire.0.start', array(
				'timeFormat' => '24'
				));
			echo $this->Form->input('Horaire.0.end', array(
				'timeFormat' => '24'
				));

			echo $this->Form->input('lieux_id', array(
				'options' => $lieux
			));	


		?>
		</fieldset>
	<?php echo $this->Form->end(__('Submit'));?>
	</div>

<?php endif; ?>