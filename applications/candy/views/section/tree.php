<?php if(!defined('APPLICATION')) die(); 
// …
?>

<h1><?php echo $this->Data('Title');?></h1>

<?php include $this->FetchViewLocation('menu', 'candy'); ?>

<?php 
//$Options = array('Class' => 'Tree', 'Tree' => $this->FullTree);
//$this->WriteTree($Options);

$PermissionAdd = CheckPermission('Candy.Sections.Add');
$PermissionDelete = CheckPermission('Candy.Sections.Delete');
$PermissionMove = CheckPermission('Candy.Sections.Move');
$PermissionSwap = CheckPermission('Candy.Sections.Swap');
$CurrentDepth = 0;
$Counter = 0;

if (count($this->Tree) > 0 ){
        
    echo "\n<ol class='Tree'>";
    // http://stackoverflow.com/questions/1310649/getting-a-modified-preorder-tree-traversal-model-nested-set-into-a-ul/#1790201

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
        if ($Node->Depth < 2) $ItemAttribute['class'] = 'Open';
        
        $Options = array();
        if ($PermissionAdd) $Options[] = Anchor(T('Add'), 'candy/section/add/'.$Node->SectionID, '');
        if (IsContentOwner($Node, 'Candy.Sections.Edit')) $Options[] = Anchor(T('Edit'), 'candy/section/edit/'.$Node->SectionID, '');
        
        if ($Node->Depth == 0) {
            // This is root
            //$Options[] = Anchor('Properties', 'candy/content/properties/'.$Node->ContentID, '');
        } else {
            if ($PermissionSwap) $Options[] = Anchor(T('Swap'), 'candy/section/swap/'.$Node->SectionID, '');
            if ($PermissionMove) $Options[] = Anchor(T('Move'), 'candy/section/move/'.$Node->SectionID, '');
            if ($PermissionDelete) $Options[] = Anchor(T('Delete'), 'candy/section/delete/'.$Node->SectionID, 'PopConfirm');
            //$Options[] = Anchor('Properties', 'candy/section/properties/'.$Node->SectionID, '');
        }
        
        echo "\n<li".Attribute($ItemAttribute).'>';
        echo '<div>';
        echo SectionAnchor($Node);
        if (count($Options) > 0) echo Wrap(implode(', ', $Options), 'span', array('class' => 'Options'));
        echo '</div>';
    }
    echo str_repeat("</li></ul>", $Node->Depth) . '</li>';	
    echo "</ol>";
}

?>

