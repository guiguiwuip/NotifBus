<div class="horaires index">
	<h2><?php echo __('Horaire'); ?></h2>
	<table class="table table-bordered table-hover" cellpadding="0" cellspacing="0">
		<tr>
				<th><?php echo $this->Paginator->sort('id'); ?></th>
				<th><?php echo $this->Paginator->sort('start'); ?></th>
				<th><?php echo $this->Paginator->sort('end'); ?></th>
				<th><?php echo $this->Paginator->sort('arret_id'); ?></th>
				<th><?php echo $this->Paginator->sort('created'); ?></th>
				<th><?php echo $this->Paginator->sort('modified'); ?></th>
				<th class="actions"><?php echo __('Actions'); ?></th>
		</tr>
		<?php foreach ($horaires as $h): ?>
		<tr>
			<td><?php echo h($h['Horaire']['id']); ?>&nbsp;</td>
			<td><?php echo h($h['Horaire']['start']); ?>&nbsp;</td>
			<td><?php echo h($h['Horaire']['end']); ?>&nbsp;</td>
			<td><?php echo h($h['Horaire']['arret_id']); ?>&nbsp;</td>
			
			<td><?php echo h($h['Horaire']['created']); ?>&nbsp;</td>
			<td><?php echo h($h['Horaire']['modified']); ?>&nbsp;</td>
			
			<td class="actions">
				<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $h['Horaire']['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $h['Horaire']['id']), null, __('Are you sure you want to delete # %s?', $h['Horaire']['id'])); ?>
			</td>
		</tr>
		<?php endforeach; ?>
	</table>
	
	<p>
	<?php
		echo $this->Paginator->counter(array(
		'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
		));
		?>	
	</p>
	
	<div class="paging">
	<?php
		echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ''));
		echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
	?>
	</div>
	
</div>

<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('New Horaire'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Users'), array('controller' => 'users', 'action' => 'list')); ?> </li>
		<li><?php echo $this->Html->link(__('List Arrets'), array('controller' => 'arrets', 'action' => 'list')); ?> </li>
		<li><?php echo $this->Html->link(__('List Horaires'), array('controller' => 'horaires', 'action' => 'list')); ?> </li>
	</ul>
</div>
