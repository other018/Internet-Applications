<div class="tournaments index">
		<div>
		<?php echo $this->Form->create('Searching'); ?>
			<fieldset>
				<legend><?php echo __('Search tournament'); ?></legend>
			<table> <tr> <td>
			<?php
				echo $this->Form->input('Phrase');
			?> </td> <td> <?php
			$options = array(1 => 'Tournament Name',
							2 => 'Discipline');
				$attributes = array('legend' => false, 'default' => 1);
				echo $this->Form->radio('Field', $options, $attributes);
			?> </td> </tr> </table>
			<?php echo $this->Form->end(__('Search')); ?>
			</fieldset>
		</div>

	<h2><?php echo __('Tournaments'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<thead>
	<tr>
			<th><?php echo $this->Paginator->sort('name'); ?></th>
			<th><?php echo $this->Paginator->sort('discipline'); ?></th>
			<th><?php echo $this->Paginator->sort('time'); ?></th>
			<th><?php echo $this->Paginator->sort('maxParticipant'); ?></th>
			<th><?php echo $this->Paginator->sort('participants'); ?></th>
			<th><?php echo $this->Paginator->sort('deadline'); ?></th>
			<th><?php echo $this->Paginator->sort('localization'); ?></th>
			<th class="actions"><?php echo __('View'); ?></th>
			<th class="actions"><?php echo __('Modify'); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($tournaments as $tournament): ?>
	<tr>
		<td><?php echo $this->Html->link(__($tournament['Tournament']['name']), array('action' => 'view', $tournament['Tournament']['id'])); ?>&nbsp;</td>
		<td><?php echo h($tournament['Tournament']['discipline']); ?>&nbsp;</td>
		<td><?php echo h($tournament['Tournament']['time']); ?>&nbsp;</td>
		<td><?php echo h($tournament['Tournament']['maxParticipant']); ?>&nbsp;</td>
		<td><?php echo h($tournament['Tournament']['participants']); ?>&nbsp;</td>
		<td><?php echo h($tournament['Tournament']['deadline']); ?>&nbsp;</td>
		<td><?php echo h($tournament['Tournament']['localization']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $tournament['Tournament']['id'])); ?>
		</td>
		<td class="actions">
			<?php if($tournament['Tournament']['organizerId'] == AuthComponent::user()['id']): ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $tournament['Tournament']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $tournament['Tournament']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $tournament['Tournament']['id']))); ?>
			<?php endif; ?>
		</td>
	</tr>
<?php endforeach; ?>
	</tbody>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
		'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
	));
	?>	</p>
	<div class="paging">
	<?php
		echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ''));
		echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
	?>
	</div>
</div>

<?php $this->start('viewActions'); ?>
	
<?php $this->end(); ?>




