<div class="users view">
<h2><?php if (AuthComponent::user()['id'] == $user['User']['id']) {
		echo __('My profile');
	} else {
		echo __('User profile');
	}?></h2>
	<dl>
		<dt><?php echo __('FirstName'); ?></dt>
		<dd>
			<?php echo h($user['User']['firstName']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('SecondName'); ?></dt>
		<dd>
			<?php echo h($user['User']['secondName']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Email'); ?></dt>
		<dd>
			<?php echo h($user['User']['email']); ?>
			&nbsp;
		</dd>

		<?php if (!empty($myFutureTournaments)): ?>		
			<dt><?php echo __('My future tournaments'); ?></dt>
			<dd>
				<ul>
					<?php foreach ($myFutureTournaments as $tournament): ?>
						<li>
							<?php echo $this->Html->link(__($tournament['Tournament']['name']), array('controller' => 'tournaments', 'action' => 'view', $tournament['Tournament']['id'])); ?>
								<?php foreach ($myMeetings as $meeting):
									if ($meeting['Meeting']['tournamentId'] == $tournament['Tournament']['id']): ?>
									<ul>
										<li><?php echo $this->Html->link(__('Round 1/'.pow(2,$meeting['Meeting']['round'])), array('controller' => 'tournaments', 'action' => 'view', $tournament['Tournament']['id'])); ?> </li>
									</ul>
									<?php endif; 
								endforeach; ?>
						</li>
					<?php endforeach; ?>
				</ul>
			</dd>
		<?php endif; ?>

		<?php if (!empty($myPastTournaments)): ?>		
			<dt><?php echo __('My past tournaments'); ?></dt>
			<dd>
				<ul>
					<?php foreach ($myPastTournaments as $tournament): ?>
						<li>
							<?php echo $this->Html->link(__($tournament['Tournament']['name']), array('controller' => 'tournaments', 'action' => 'view', $tournament['Tournament']['id'])); ?>
								<?php foreach ($myMeetings as $meeting):
									if ($meeting['Meeting']['tournamentId'] == $tournament['Tournament']['id']): ?>
									<ul>
										<li><?php echo $this->Html->link(__('Round 1/'.pow(2,$meeting['Meeting']['round'])), array('controller' => 'tournaments', 'action' => 'view', $tournament['Tournament']['id'])); ?> </li>
									</ul>
									<?php endif; endforeach; ?>
						</li>
					<?php endforeach; ?>
				</ul>
			</dd>
		<?php endif; ?>


	</dl>
</div>

<?php $this->start('viewActions'); ?>
	<li><?php echo $this->Html->link(__('Change Password'), array('action' => 'changePassword', $user['User']['id'])); ?> </li>
	<li><?php echo $this->Html->link(__('Edit User'), array('action' => 'edit', $user['User']['id'])); ?> </li>
<?php $this->end(); ?>
