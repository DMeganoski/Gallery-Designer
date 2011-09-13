<?php if (!defined('APPLICATION')) exit();

//$this->Pager->Wrapper = '<tr><td colspan="4" %1$s>%2$s</td></tr>';

$PermissionEdit = CheckPermission('Candy.Chunks.Edit');
$PermissionDelete = CheckPermission('Candy.Chunks.Delete');
?>

<h1><?php echo $this->Data('Title');?></h1>


<?php include $this->FetchViewLocation('menu', 'candy'); ?>


<?php if ($this->Chunks->NumRows() == 0) {
	echo "<div class='Info Empty'>".T('There are nothing.')."</div>";
	return;
}
?>


<?php echo $this->Pager->ToString('less'); ?>

<table class="AltRows" style="width:100%">

<tbody>

<?php foreach ($this->Chunks as $Chunk) {
	//d($Chunk);
	$Id = $Chunk->ChunkID;
	$Name = $Chunk->Name;
	$Options = array();
	if ($PermissionEdit) $Options[] = Anchor(T('Edit'), 'candy/chunk/update/'.$Id, '');
	if ($PermissionDelete) $Options[] = Anchor(T('Delete'), 'candy/chunk/delete/'.$Id, 'PopConfirm');
	?>
	<tr>
	<td><?php echo $Id;?></td>
	<td><?php 
		if ($Chunk->Url) $Name = Anchor($Name, $Chunk->Url);
		echo $Name;
	?></td>
	<td><?php echo Gdn_Format::Date($Chunk->DateUpdated);?></td>
	<td class="Options"><?php echo implode('', $Options);?></td>
	</tr>
	<?php } ?>

</tbody>
</table>
	
<?php echo $this->Pager->ToString('more'); ?>
	



