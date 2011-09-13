<?php if(!defined('APPLICATION')) die(); 
// …
$Alt = 1;
$PermissionRoutesManage = CheckPermission('Candy.Routes.Manage');
?>

<h1><?php echo $this->Data('Title');?></h1>

<?php include $this->FetchViewLocation('menu', 'candy'); ?>

<table class="AltRows">
<thead>
<tr>
	<th><?php echo HoverHelp('URI', '');?></th>
	<th><?php echo HoverHelp('RequestUri', '');?></th>
	<th><?php echo HoverHelp(T('Options'), '');?></th>
</tr>
</thead>
<tbody>

<?php foreach ($this->RouteDataSet as $Route) {
	$Alt = !$Alt;
	$Row = '';
	$Row .= '<td>' . Anchor($Route->URI, $Route->URI) . '</td>';
	$Row .= '<td>' . Anchor($Route->RequestUri, $Route->RequestUri) . '</td>';
	$Options = array();
	if ($PermissionRoutesManage) {
		$Options[] = Anchor(T('Delete'), 'candy/routes/delete/'.base64_encode($Route->URI), 'SmallButton RemoveItem');
	}
	$Row .= '<td>' . implode(' ', $Options) . '</td>';
	
	echo Wrap($Row, 'tr', array('class' => $Alt ? 'Alt' : ''));
}
?>
</tbody>
</table>