<div class="users form">
<?php echo $this->Form->create('User'); ?>
	<fieldset>
		<legend><?php echo __('Change password'); ?></legend>
	<?php
		echo $this->Form->input('password');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Change password')); ?>
</div>

<?php $this->start('viewActions'); ?>

<?php $this->end(); ?>