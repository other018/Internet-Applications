<div class="sponsors form">
<?php echo $this->Form->create('Sponsor', array('enctype' => 'multipart/form-data')); ?>
	<fieldset>
		<legend><?php echo __('Add Sponsor'); ?></legend>
	<?php
		echo $this->Form->input('sponsorName');
		echo $this->Form->input('upload', array('type' => 'file'));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>

<?php $this->start('viewActions'); ?>

<?php $this->end(); ?>