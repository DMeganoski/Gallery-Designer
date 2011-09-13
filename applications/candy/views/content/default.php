<?php if(!defined('APPLICATION')) die(); 

$Content = $this->Data('Content');

$this->EventArguments['Format'] =& $Content->Format;
$this->EventArguments['Body'] =& $Content->Body;
$this->FireEvent('BeforeBodyFormat');
$BodyFormat = Gdn_Format::To($Content->Body, $Content->Format);

$TextHeader = PqDocument($BodyFormat)->Find('h1')->Text();
?>

<?php if (!$TextHeader) echo '<h1>', $Content->Title, '</h1>'; ?>

<div class="Body"><?php	echo $BodyFormat;?></div>
