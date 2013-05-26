<div class="arrets view">

	<h2>Arrêts</h2>
	<table class="table table-bordered table-hover" cellpadding="0" cellspacing="0">
		<tr>
				<th><?php echo $this->Paginator->sort('id'); ?></th>
				<th><?php echo $this->Paginator->sort('arrets'); ?></th>
				<th><?php echo $this->Paginator->sort('ligne_id'); ?></th>
				<th><?php echo $this->Paginator->sort('sens'); ?></th>
				<th><?php echo $this->Paginator->sort('options'); ?></th>
				<th><?php echo $this->Paginator->sort('delai'); ?></th>
				<th><?php echo $this->Paginator->sort('lieux_id'); ?></th>
				<th><?php echo $this->Paginator->sort('user_id'); ?></th>
				<th><?php echo $this->Paginator->sort('created'); ?></th>
				<th><?php echo $this->Paginator->sort('modified'); ?></th>
				<th class="actions">Actions</th>
		</tr>
	<?php foreach ($arrets as $arret): ?>
		<tr>
			<td><?php echo h($arret['Arret']['id']); ?>&nbsp;</td>
			<td><?php echo h($arret['Arret']['arret']); ?>&nbsp;</td>
			<td><?php echo h($arret['Arret']['ligne_id']); ?>&nbsp;</td>
			<td><?php echo h($arret['Arret']['sens']); ?>&nbsp;</td>
			<td><?php echo h($arret['Arret']['options']); ?>&nbsp;</td>
			<td><?php echo h($arret['Arret']['delai']); ?>&nbsp;</td>
			<td><?php echo h($arret['Arret']['lieux_id']); ?>&nbsp;</td>
			<td>
				<?php 
					echo $this->Html->link(
						$arret['User']['username'],
						array('controller' => 'users', 'action' => 'edit', $arret['User']['id'], 'admin' => true, 'prefix' => 'admin')
					);
				?>
				&nbsp;
			</td>
			<td><?php echo h($arret['Arret']['created']); ?>&nbsp;</td>
			<td><?php echo h($arret['Arret']['modified']); ?>&nbsp;</td>
			
			<td class="actions">
				<?php echo $this->Html->link('Edit', array('action' => 'edit', $arret['Arret']['id'])); ?>
				<?php 
					echo $this->Form->postLink(
						__('Delete'),
						'/admin/arrets/delete/'.$arret['Arret']['id'],
						null,
						__('Are you sure you want to delete # %s?', $arret['Arret']['id'])
					); 
				?>
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
		echo $this->Paginator->prev('< ' . __('previous '), array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ''));
		echo $this->Paginator->next(__(' next') . ' >', array(), null, array('class' => 'next disabled'));
	?>
	</div></div>



<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('New Arret'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Users'), array('controller' => 'users', 'action' => 'list')); ?> </li>
		<li><?php echo $this->Html->link(__('List Horaires'), array('controller' => 'horaires', 'action' => 'list')); ?> </li>
		<li><?php echo $this->Html->link(__('List Lieux'), array('controller' => 'lieux', 'action' => 'list')); ?> </li>
	</ul>
</div>

