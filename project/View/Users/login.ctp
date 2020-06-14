<div class="users form">
<?php echo $this->Form->create('User'); ?>
	<fieldset>
		<legend><?php echo __('Log in'); ?></legend>
	<?php
		echo $this->Form->input('email');
		echo $this->Form->input('password');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Login')); ?>
<?php echo $this->Html->link(__('Forgot password'), array('controller' => 'Users', 'action' => 'forgotPassword')); ?>
</div>

<?php $this->start('viewActions'); ?>

<?php $this->end(); ?>