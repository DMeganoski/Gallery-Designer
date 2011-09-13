<?php if (!defined('APPLICATION')) die(); 

$Content = $this->Data('Content');
?>

<h1><?php echo $this->Data('Title'); 
if ($this->Editing) echo ', Request URI: ' . 'candy/content/page/'.$Content->PageID;
?></h1>

<?php include $this->FetchViewLocation('menu', 'candy'); ?>

<?php 
echo $this->Form->Open(array('enctype' => 'multipart/form-data'));
echo $this->Form->Errors(); 
?>


<ul class="EditForm">
<?php 
$this->FireEvent('BeforeInputFieldsRender'); 
echo Wrap($this->Form->TextBox('Title', array('placeholder' => T('Title'))), 'li');
echo Wrap(
	$this->Form->Label('Published', 'Visible').
	$this->Form->CheckBox('Visible'),
	'li'
);
$FormatOptions = LocalizedOptions(array('Text', 'xHtml', 'Html', 'Markdown', 'Raw'));
echo Wrap(
	$this->Form->Label('Format', 'Format').
	$this->Form->DropDown('Format', $FormatOptions),
	'li'
);
$DropDownOptions = array('IncludeNull' => True, 'TextField' => 'Name', 'ValueField' => 'SectionID');
echo Wrap(
	$this->Form->Label('Section', 'SectionID').
	$this->Form->DropDown('SectionID', $this->Tree, $DropDownOptions),
'li');

if (!$this->Editing) {
	echo Wrap(
		$this->Form->Label('Create section', 'CreateSection').
		$this->Form->CheckBox('CreateSection'), 
		'li'
	);
}

echo Wrap(
	$this->Form->Label('URI', 'URI').
	$this->Form->TextBox('URI'), 
	'li'
);

echo Wrap(
	$this->Form->Label('Meta tag (description)', 'MetaDescription') .
	$this->Form->TextBox('MetaDescription', array('Multiline' => True))
, 'li', array('class' => 'MetaFields Hidden'));
echo Wrap(
	$this->Form->Label('Meta tag (keywords)', 'MetaKeywords') .
	$this->Form->TextBox('MetaKeywords', array('Multiline' => False))
, 'li', array('class' => 'MetaFields Hidden'));
echo Wrap(
	$this->Form->Label('Meta tag (robots)', 'MetaRobots') .
	$this->Form->TextBox('MetaRobots', array('Multiline' => False))
, 'li', array('class' => 'MetaFields Hidden'));

?>

<?php
echo Wrap(
	Wrap(T('Meta tags?'), 'a', array('href' => '#', 'class' => 'ToggleButton MetaFields')) . 
	Wrap(T('Slug from title?'), 'a', array('href' => '#', 'class' => '', 'id' => 'GetSlugButton')) . 
	$this->Form->TextBox('Body', array('Multiline' => True, 'placeholder' => T('Body'))),
	'li'
);
?>

<?php
$this->FireEvent('AfterInputFieldsRender'); 
?>

</ul>
<?php 
echo $this->Form->Button('Save');
echo ($this->Editing) ? $this->Form->Button('Delete') : '';
$this->FireEvent('BeforeCloseForm');
echo $this->Form->Close();
?>
