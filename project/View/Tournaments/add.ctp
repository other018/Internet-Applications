<div class="tournaments form">
<?php echo $this->Form->create('Tournament'); ?>
	<fieldset>
		<legend><?php echo __('Add Tournament'); ?></legend>
	<?php
		echo $this->Form->input('name');
		echo $this->Form->input('discipline');
		echo $this->Form->input('time');
		echo $this->Form->input('maxParticipant');
		echo $this->Form->input('deadline');
		echo $this->Form->input('localization');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Create tournament')); ?>
</div>


<?php $this->start('viewActions'); ?>

<?php $this->end(); ?>