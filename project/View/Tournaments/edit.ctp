<div class="tournaments form">
<?php echo $this->Form->create('Tournament'); ?>
	<fieldset>
		<legend><?php echo __('Edit Tournament'); ?></legend>
	<?php
		echo $this->Form->input('name');
		echo $this->Form->input('discipline');
		echo $this->Form->input('time');
		echo $this->Form->input('maxParticipant');
		echo $this->Form->input('deadline');
		echo $this->Form->input('localization');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Save changes')); ?>

<?php if (!empty($oldTournament['Sponsor'])): ?>
	<fieldset>
		<legend><?php echo __('Sponsors'); ?></legend>
	</fieldset>
	<table>
		<tr>
			<th> Image </th>
			<th> Sponsor Name </th>
			<th> Actions </th>
		</tr>

		<?php foreach ($oldTournament['Sponsor'] as $sponsor): ?>
			<tr>
				<td style="width:30%"><img src=<?='/cake/img/'.$sponsor['fileName'] ?> alt=<?=$sponsor['sponsorName'] ?> class="image"></td>
				<td><?= $sponsor['sponsorName'] ?></td>
				<td class="actions">
					<ul>
						<li><?php echo $this->Html->link(__('Edit sponsor'), array('controller'=>'sponsors', 'action' => 'edit', $sponsor['id'])); ?></li>
						<li><?php echo $this->Form->postLink(__('Remove sponsor'), array('controller'=>'sponsors', 'action' => 'delete', $sponsor['id']), array('confirm' => __('Are you sure you want to delete # %s?', $sponsor['id']))); ?> </li>
					</ul>
				</td>
			</tr>
		<?php endforeach; ?>
	</table>
<?php endif; ?>

<?php echo $this->Html->link(__('Add new sponsor'), array('controller' => 'sponsors', 'action'=> 'add',
				$oldTournament['Tournament']['id']), array('class' => 'greenButton'));		
?>

</div>

<?php $this->start('viewActions'); ?>
	<?php if ($this->Form->value('Tournament.deadline') >= date("Y-m-d H:i:s")) : ?>
		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Tournament.id')), array('confirm' => __('Are you sure you want to delete #%s?', $this->Form->value('Tournament.name')))); ?></li>
	<?php endif; ?>
<?php $this->end(); ?>
