<div class="users form">
<?php echo $this->Form->create('User'); ?>
	<fieldset>
		<legend><?php echo __('Register'); ?></legend>
	<?php
		echo $this->Form->input('firstName');
		echo $this->Form->input('secondName');
		echo $this->Form->input('email');
		echo $this->Form->input('password');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Create account')); ?>
</div>

<?php $this->start('viewActions'); ?>
	
<?php $this->end(); ?>