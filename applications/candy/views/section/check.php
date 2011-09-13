<?php if(!defined('APPLICATION')) die(); 
// …
?>

<h1><?php echo $this->Data('Title');?></h1>

<?php include $this->FetchViewLocation('menu', 'candy'); ?>

<?php if ($this->CorruptedData->NumRows() == 0) {
	echo Wrap(T('Seems, all is OK.'), 'div', array('class' => 'Info'));
} else { ?>
		<table class="AltRows">
		<tbody>
	<?php foreach ($this->CorruptedData as $Node) { ?>
		<tr>
			<td><?php echo $Node->SectionID;?></td>
			<td> <?php echo $Node->Name;?></td>
			<td> L:<?php echo $Node->TreeLeft;?>, R:<?php echo $Node->TreeRight;?>, D:<?php echo $Node->Depth;?></td>
		</tr>
	<?php } ?>
		</tbody>
		</table>

<?php } ?>
