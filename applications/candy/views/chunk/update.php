<?php if (!defined('APPLICATION')) exit();

?>

<h1><?php echo $this->Data('Title');?></h1>

<?php include $this->FetchViewLocation('menu', 'candy'); ?>

<?php
echo $this->Form->Open();
echo $this->Form->Errors();
?>

<ul class="EditForm">
<li>
<?php
echo Wrap(
	$this->Form->Label('Name', 'Name').
	$this->Form->TextBox('Name'), 
	'li'
);
echo Wrap(
	$this->Form->Label('@URL', 'Url').
	$this->Form->TextBox('Url'), 
	'li'
);
$FormatOptions = LocalizedOptions(array('Text', 'xHtml', 'Html', 'Markdown', 'Raw'));
echo Wrap(
	$this->Form->Label('Format', 'Format').
	$this->Form->DropDown('Format', $FormatOptions),
	'li'
);

echo Wrap(
	$this->Form->TextBox('Body', array('Multiline' => True, 'placeholder' => T('Body'))),
	'li'
);
?>
</li>
</ul>
<?php
echo $this->Form->Button('Save');
$this->Form->Close();
?>