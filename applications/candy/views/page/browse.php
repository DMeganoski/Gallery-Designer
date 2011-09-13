<?php if(!defined('APPLICATION')) die(); 


$PermissionDelete = CheckPermission('Candy.Pages.Delete');
?>

<h1><?php echo $this->Data('Title');?></h1>

<?php include $this->FetchViewLocation('menu', 'candy'); ?>

<table class="AltRows" style="width:100%">
<!--<tr>
<thead>
	<th></th>
</thead>
</tr> -->

<tbody>
<?php foreach ($this->Pages as $Page) {
	$Options = array();
	if (IsContentOwner($Page, 'Candy.Pages.Edit')) $Options[] = Anchor(T('Edit'), 'candy/page/edit/'.$Page->PageID, '');
	if ($PermissionDelete) $Options[] = Anchor(T('Delete'), 'candy/page/delete/'.$Page->PageID, '');
	?>
	<tr>
	<td><?php echo Anchor($Page->Title, 'content/page/'.$Page->PageID);?></td>
	<td><?php echo $Page->Visible ? 'o' : 'x';?></td>
	<td class="Options"><?php echo implode('', $Options);?></td>
	</tr>
	<?php } ?>
</tbody>
</table>