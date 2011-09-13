<?php if (!defined('APPLICATION')) die(); ?>

<h1><?php echo $this->Data('Title'); ?></h1>

<?php 
echo $this->Form->Open();
echo $this->Form->Errors(); 

?>

<ul>

<?php

echo Wrap(
	$this->Form->Label('Name', 'Name').
	$this->Form->TextBox('Name'), 'li');

echo Wrap(
	$this->Form->Label('URI', 'URI').
	$this->Form->TextBox('URI'), 'li');
	
echo Wrap(
	$this->Form->Label('Request URI', 'RequestUri').
	$this->Form->TextBox('RequestUri'), 'li');

echo Wrap(
	$this->Form->Label('Url', 'Url').
	$this->Form->TextBox('Url'), 'li');

?>

	
</ul>
<?php 
echo $this->Form->Button('Save');
echo $this->Form->Close();
?>
