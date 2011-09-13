<?php if (!defined('APPLICATION')) exit(); 

$CheckViewingProperty = 'PageID';
$Page = GetValueR('Page', $this->_Sender);
$ViewingID = isset($Page->{$CheckViewingProperty}) ? $Page->$CheckViewingProperty : '';

// TODO: MOVE TO sections.php
?>


<div class="Box BoxSections">
	<ul class="PanelInfo">
<?php
foreach ($this->Data('Items') as $Item) {
	$CssClass = ($ViewingID == $Item->{$CheckViewingProperty} ? ' Active' : '');
	echo "\n<li".Attribute('class', $CssClass).'>';
/*	$Url = GetValue('URI', $Item);
	if (!$Url) 
	echo Anchor($Item->Title, $Url);*/
	//echo SectionAnchor($Item);
	echo Anchor($Item->Title, GetValue('URI', $Item));
	echo '</li>';
}

?>

</ul>
</div>
