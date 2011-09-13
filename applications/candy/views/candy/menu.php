<?php if (!defined('APPLICATION')) exit(); 


$Options = array();
if (CheckPermission('Candy.Settings.View')) {
	$Options[] = Anchor(T('Sections'), 'candy/section/tree', 'Button SmallButton');
	//if (C('Debug')) $Options[] = Anchor('Check tree', 'candy/node/check', 'Button SmallButton');
	$Options[] = Anchor(T('Pages'), 'candy/page/browse', 'Button SmallButton');
	$Options[] = Anchor(T('Routes'), 'candy/routes', 'Button SmallButton');
}
if (CheckPermission('Candy.Pages.Add')) $Options[] = Anchor(T('Add page'), 'candy/page/addnew', 'Button SmallButton');
$Options[] = Anchor(T('Chunks'), 'candy/chunk/browse', 'Button SmallButton');
$Options[] = Anchor(T('Add chunk'), 'candy/chunk/update', 'Button SmallButton');

?>

<div class="FilterMenu" style="padding-bottom: 10px;">
<?php echo implode("\n", $Options); ?>
</div>