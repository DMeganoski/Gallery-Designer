<?php if(!defined('APPLICATION')) exit(); ?>

<h1><?php echo $this->Data('Title');?></h1>

<div id="ContentMap" class="Body">
<?php
echo "\n<ol class='Tree'>";

$FirstDepth = $this->Tree->FirstRow()->Depth;
$CurrentDepth = $FirstDepth;
$Counter = 0;

if ($this->AddHomeTreeNode) {
	echo "<li>", Anchor(T('Home'), '/'), '</li>';
}

foreach ($this->Tree as $Node) {
	
	if ($Node->Depth > $CurrentDepth) echo "<ul>";
	elseif ($Node->Depth < $CurrentDepth) {
		echo str_repeat("</li></ul>", $CurrentDepth - $Node->Depth), '</li>';
	} else {
		if ($Counter > 0) echo "</li>";
	}
	
	$CurrentDepth = $Node->Depth;
	++$Counter;
	
	$ItemAttribute = array('id' => 'Tree_'.$Node->SectionID);
	
	echo "\n<li".Attribute($ItemAttribute).'>';
	echo SectionAnchor($Node);
}
echo str_repeat("</li></ul>", $Node->Depth - $FirstDepth) . '</li>';	
echo "</ol>";
?>
</div>