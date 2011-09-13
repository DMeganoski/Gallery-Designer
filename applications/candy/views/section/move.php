<?php if (!defined('APPLICATION')) die(); ?>

<h1><?php echo $this->Data('Title');?></h1>

<?php echo $this->Form->Open(); ?>
<?php echo $this->Form->Errors(); ?>
<ul>

<li>
<?php 
echo $this->Form->Label('Position', 'SecondID');
echo $this->Form->DropDown('SecondID', $this->FullTreeOptions, array('TextField' => 'Name', 'ValueField' => 'SectionID'));
?>
</li>

</ul>
<?php 
echo $this->Form->Button('Save');
echo $this->Form->Close(); 
?>