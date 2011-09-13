<?php if (!defined('APPLICATION')) die(); ?>

<h1><?php echo $this->Data('Title'); ?></h1>

<?php 
echo $this->Form->Open();
echo $this->Form->Errors(); 
?>

<ul class="EditForm">
<?php 
echo Wrap(
	$this->Form->Label('Published', 'Visible').
	$this->Form->CheckBox('Visible'),
	'li'
);
?>

</ul>
<?php 
echo $this->Form->Button('Save');
echo $this->Form->Close();
?>
