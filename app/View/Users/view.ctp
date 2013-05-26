<div class="users view">
<h2><?php  echo __('User'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($user['User']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Username'); ?></dt>
		<dd>
			<?php echo h($user['User']['username']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Password'); ?></dt>
		<dd>
			<?php echo h($user['User']['password']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Group'); ?></dt>
		<dd>
			<?php echo h($user['User']['group']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($user['User']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($user['User']['modified']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit User'), array('action' => 'edit', $user['User']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete User'), array('action' => 'delete', $user['User']['id']), null, __('Are you sure you want to delete # %s?', $user['User']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Users'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New User'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Arrets'), array('controller' => 'arrets', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Arret'), array('controller' => 'arrets', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php echo __('Related Arrets'); ?></h3>
	<?php if (!empty($user['Arret'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Arret'); ?></th>
		<th><?php echo __('Ligne'); ?></th>
		<th><?php echo __('Sens'); ?></th>
		<th><?php echo __('User Id'); ?></th>
		<th><?php echo __('Modified'); ?></th>
		<th><?php echo __('Created'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($user['Arret'] as $arret): ?>
		<tr>
			<td><?php echo $arret['id']; ?></td>
			<td><?php echo $arret['arret']; ?></td>
			<td><?php echo $arret['ligne']; ?></td>
			<td><?php echo $arret['sens']; ?></td>
			<td><?php echo $arret['user_id']; ?></td>
			<td><?php echo $arret['modified']; ?></td>
			<td><?php echo $arret['created']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'arrets', 'action' => 'view', $arret['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'arrets', 'action' => 'edit', $arret['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'arrets', 'action' => 'delete', $arret['id']), null, __('Are you sure you want to delete # %s?', $arret['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Arret'), array('controller' => 'arrets', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
