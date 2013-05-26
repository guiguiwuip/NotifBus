<div class="lieux index">
	<h2><?php echo __('Lieux'); ?></h2>
	<table class="table table-bordered table-hover" cellpadding="0" cellspacing="0">
		<tr>
				<th><?php echo $this->Paginator->sort('id'); ?></th>
				<th><?php echo $this->Paginator->sort('name'); ?></th>
				<th><?php echo $this->Paginator->sort('lat'); ?></th>
				<th><?php echo $this->Paginator->sort('lng'); ?></th>
				<th class="actions"><?php echo __('Actions'); ?></th>
		</tr>
		<?php foreach ($lieux as $l): ?>
		<tr>
			<td><?php echo h($l['Lieux']['id']); ?>&nbsp;</td>
			<td><?php echo h($l['Lieux']['name']); ?>&nbsp;</td>
			<td><?php echo h($l['Lieux']['lat']); ?>&nbsp;</td>
			<td><?php echo h($l['Lieux']['lng']); ?>&nbsp;</td>
						
			<td class="actions">
				<?php echo $this->Html->link(" Editer", array('action' => 'edit', $l['Lieux']['id']), array('class' => 'fui-new-24')); ?>
				<?php echo $this->Form->postLink(' Supprimer', array('action' => 'delete', $l['Lieux']['id']), array('class' => 'fui-cross-24'), __('Êtes vous sûr de vouloir supprimer ce lieu ?', $l['Lieux']['id'])); ?>
			</td>
		</tr>
		<?php endforeach; ?>
	</table>
	
	
	<div class="paging">
	<?php
		// echo $this->Paginator->prev('< ' . __('previous '), array(), null, array('class' => 'prev disabled'));
		// echo $this->Paginator->numbers(array('separator' => ''));
		// echo $this->Paginator->next(__(' next') . ' >', array(), null, array('class' => 'next disabled'));
	?>
	</div>
	
</div>

<div>
	<?php echo $this->Html->link("Nouveau Lieu", array('action' => 'add'), array('class' => 'btn btn-large')); ?> 
</div>

