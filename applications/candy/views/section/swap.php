<?php if (!defined('APPLICATION')) die(); ?>

<h1><?php echo $this->Data('Title');?></h1>

<?php echo $this->Form->Open(); ?>
<?php echo $this->Form->Errors(); ?>
<ul>

<?php if (is_object($this->Branch)) { ?>
<li>
<?php 
echo $this->Form->Label('Position (Childrens)', 'AllSecondID');
echo $this->Form->DropDown('AllSecondID', $this->Branch, array('TextField' => 'Name', 'ValueField' => 'SectionID'));
$ChoosePosition = array('before' => 'Before', 'after' => 'After');
echo ' ', $this->Form->DropDown('Position', $ChoosePosition, array('IncludeNull' => True));
?>
</li>
<?php } ?>

<li>
<?php 
echo $this->Form->Label('Position (Tree)', 'TreeSecondID');
echo $this->Form->DropDown('TreeSecondID', $this->FullTreeOptions);
?>
</li>

</ul>
<?php 
echo $this->Form->Button('Save');
echo $this->Form->Close(); 
?>