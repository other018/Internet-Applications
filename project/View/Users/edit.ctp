<div class="users form">
<?php echo $this->Form->create('User'); ?>
	<fieldset>
		<legend><?php echo __('Edit User'); ?></legend>
	<?php
		echo $this->Form->input('firstName', array('default'=>$oldUser['firstName']));
		echo $this->Form->input('secondName', array('default'=>$oldUser['secondName']));
		echo $this->Form->input('email', array('default'=>$oldUser['email']));
	?>
	</fieldset>

<?php echo $this->Form->end(__('Submit')); ?>
</div>

<?php $this->start('viewActions'); ?>

<?php $this->end(); ?>
