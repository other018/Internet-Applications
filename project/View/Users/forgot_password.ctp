<div class="users form">
<?php echo $this->Form->create('User'); ?>
	<fieldset>
		<legend><?php echo __('Forgot password'); ?></legend>
	<?php
		echo $this->Form->input('email');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Create new password')); ?>
</div>

<?php $this->start('viewActions'); ?>

<?php $this->end(); ?>