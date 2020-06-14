<div class="tournaments view">
<h2><?php echo __('Tournament'); ?></h2>
	<dl>
		<dt><?php echo __('Name'); ?></dt>
		<dd>
			<?php echo h($tournament['Tournament']['name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Discipline'); ?></dt>
		<dd>
			<?php echo h($tournament['Tournament']['discipline']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Organizer'); ?></dt>
		<dd>
			<?php echo h($organizator['User']['firstName'].' '.$organizator['User']['secondName']); ?>
			<br />
			<?php echo h('Contact: '.$organizator['User']['email']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Participants'); ?></dt>
		<dd>
			<?php echo h($tournament['Tournament']['participants']); ?>
			<?php echo 'of' ?>
			<?php echo h($tournament['Tournament']['maxParticipant']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Deadline'); ?></dt>
		<dd>
			<?php echo h($tournament['Tournament']['deadline']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Tournament time'); ?></dt>
		<dd>
			<?php echo h($tournament['Tournament']['time']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Localization'); ?></dt>
		<dd>
			<?php echo h($tournament['Tournament']['localization']); ?>
			<br />
			<iframe src= <?php echo "https://maps.google.com/?q=".str_replace(' ','+',$tournament['Tournament']['localization']) ?> title="Tournament place" > </iframe>
			<div>
			If map doesn't work properly try visit:
			<a href = <?php echo "https://maps.google.com/?q=".str_replace(' ','+',$tournament['Tournament']['localization']) ?>> Google Maps </a>
			</div>
			
			&nbsp;
		</dd>
		<?php if (!empty($tournament['Sponsor'])): ?>		
			<dt><?php echo __('Sponsors'); ?></dt>
			<dd>
					<div class="sponsors">
						<?php foreach ($tournament['Sponsor'] as $sponsor): ?>
							<div class="sponsor">
								<img src=<?='/cake/img/'.$sponsor['fileName'] ?> alt=<?= $sponsor['sponsorName'] ?> class="image" style="width:100%">
								<div class="sponsortext"><?= $sponsor['sponsorName'] ?></div>
							</div>
						<?php endforeach; ?>
					</div>
			</dd>
		<?php endif; ?>

	</dl>
	<br />

	<?php
		if (empty($participant)) {
			if ($tournament['Tournament']['deadline'] > date("Y-m-d H:i:s") &&
					$tournament['Tournament']['participants'] < $tournament['Tournament']['maxParticipant']) {
				echo $this->Html->link(__('Join'), array('controller'=> 'participants', 'action' => 'add', $tournament['Tournament']['id']), 
					array('class' => 'greenButton'));
			}
		} else {
			if ($tournament['Tournament']['deadline'] > (date("Y-m-d H:i:s"))) {
				
				echo $this->Form->postLink(__('Un Join'),
					array('controller'=> 'participants', 'action' => 'unJoin', $tournament['Tournament']['id']),
					array('confirm' => __('Are you sure you want to unjoin tournament?', $tournament['Tournament']['id']),
						  'class' => 'redButton'));
			}
		}
	?>

<!------------------------------------------------------------------------------------------------------------>


	<?php
		function sortMeetingMethod($a,$b) {
			if ($a['round'] > $b['round']) return -1;
			if ($a['round'] < $b['round']) return 1;
			if ($a['number'] < $b['number']) return -1;
			if ($a['number'] > $b['number']) return 1;
		}

	?>

	<?php
		if (!empty($tournament['Meeting'])): 
			usort($tournament['Meeting'], "sortMeetingMethod");
		?>
		<table class='rozstawienie' cellpadding = "0" cellspacing = "0">
		<tr>
		<?php
			$maxRound = $tournament['Meeting'][0]['round'];
			$actualRound = $maxRound;

				foreach ($tournament['Meeting'] as $meeting): 
			if ($meeting['round'] != $actualRound) :
				$actualRound = $meeting['round'];
				?>
				</tr>
				<tr>
				<?php 
			endif; ?>
				<td colspan = <?= pow(2, $maxRound-$actualRound)?>>
					<?php
					$u1Name = ($meeting['player1']!==null) ? $userNames[$meeting['player1']] : null;
					$u2Name = ($meeting['player2']!==null) ? $userNames[$meeting['player2']] : null;
					
					$createResult = 0;
					if ($meeting['player1'] !== null && $meeting['player2']!==null) {
						if (empty($participant)) {
							$playerNum = null;
						} else if ($participant['Participant']['id'] === $meeting['player1']){
							$playerNum = 1;
						} else if ($participant['Participant']['id'] === $meeting['player2']){
							$playerNum = 2;
						} else {
							$playerNum = null;
						}

						if ($playerNum !== null && $meeting['winner'.$playerNum]===null) {
							$createResult = 1;
						} else {
							$createResult = 0;
						}
						
					}
					if ($createResult === 0) :
					?>
					<ul>
						<?php if ($meeting['winner'] == 1): ?>
							<li class='winner'>
						<?php elseif ($meeting['winner'] == 2): ?>
							<li class='looser'>
						<?php else: ?>
							<li>
						<?php endif; ?>
						<?php echo $u1Name['firstName'].' '.$u1Name['secondName'] ?></li>

						<?php if ($meeting['winner'] == 2): ?>
							<li class='winner'>
						<?php elseif ($meeting['winner'] == 1): ?>
							<li class='looser'>
						<?php else: ?>
							<li>
						<?php endif; ?>
						<?php echo $u2Name['firstName'].' '.$u2Name['secondName'] ?></li>
					</ul>
					<?php else: ?>

						<?php
							$options = array(1 => $u1Name['firstName'].' '.$u1Name['secondName'],
											2 => $u2Name['firstName'].' '.$u2Name['secondName']);
							$attributes = array('legend' => false, 'default' => $playerNum);
							echo $this->Form->create('Meeting');
							echo $this->Form->hidden('id', array('value' => $meeting['id']));
							echo $this->Form->radio('winner'.$playerNum, $options, $attributes);
							echo $this->Form->end('Send result');
						?>
					<?php endif; ?>
				</td>
		<?php endforeach; ?>
		</tr>
		</table>
	<?php endif; ?>





<!------------------------------------------------------------------------------------------------------------>


</div>


<?php $this->start('viewActions'); ?>
	<?php if (AuthComponent::user()['id'] == $tournament['Tournament']['organizerId']): ?>
		<li><?php echo $this->Html->link(__('Edit Tournament'), array('action' => 'edit', $tournament['Tournament']['id'])); ?> </li>
		<?php if ($tournament['Tournament']['deadline'] >= date("Y-m-d H:i:s")) : ?>
			<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $tournament['Tournament']['id']), array('confirm' => __('Are you sure you want to delete #%s?', $tournament['Tournament']['name']))); ?></li>
		<?php endif; ?>
	<?php endif; ?>	
<?php $this->end(); ?>

<!--
<div class="related">
	<h3><?php echo __('Related Participants'); ?></h3>
	<?php if (!empty($tournament['Participant'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Tournament Id'); ?></th>
		<th><?php echo __('User Id'); ?></th>
		<th><?php echo __('LicenceNumber'); ?></th>
		<th><?php echo __('RankPosition'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($tournament['Participant'] as $participant): ?>
		<tr>
			<td><?php echo $participant['id']; ?></td>
			<td><?php echo $participant['tournamentId']; ?></td>
			<td><?php echo $participant['userId']; ?></td>
			<td><?php echo $participant['licenceNumber']; ?></td>
			<td><?php echo $participant['rankPosition']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'participants', 'action' => 'view', $participant['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'participants', 'action' => 'edit', $participant['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'participants', 'action' => 'delete', $participant['id']), array('confirm' => __('Are you sure you want to delete # %s?', $participant['id']))); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Participant'), array('controller' => 'participants', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
<div class="related">
	<h3><?php echo __('Related Meetings'); ?></h3>
	<?php if (!empty($tournament['Meeting'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('TournamentId'); ?></th>
		<th><?php echo __('Player1'); ?></th>
		<th><?php echo __('Player2'); ?></th>
		<th><?php echo __('Winner1'); ?></th>
		<th><?php echo __('Winner2'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($tournament['Meeting'] as $meeting): ?>
	
		<tr>
			<td><?php echo $meeting['id']; ?></td>
			<td><?php echo $meeting['tournamentId']; ?></td>
			<td><?php echo $meeting['player1']; ?></td>
			<td><?php echo $meeting['player2']; ?></td>
			<td><?php echo $meeting['winner1']; ?></td>
			<td><?php echo $meeting['winner2']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'meetings', 'action' => 'view', $meeting['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'meetings', 'action' => 'edit', $meeting['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'meetings', 'action' => 'delete', $meeting['id']), array('confirm' => __('Are you sure you want to delete # %s?', $meeting['id']))); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Meeting'), array('controller' => 'meetings', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
	-->