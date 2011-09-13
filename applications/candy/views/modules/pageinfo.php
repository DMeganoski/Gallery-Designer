<?php if (!defined('APPLICATION')) exit(); 

$Page = GetValue('Content', $Data);
$Title = GetValue('Title', $Data, $Page->Title);
$TargetUrl = $this->_Sender->SelfUrl;
// Wrapper box <div class="Box"> in Module::String()
$PermissionEdit = IsContentOwner($Page, 'Candy.Pages.Edit');
?>

<h4><?php echo Anchor($Title, 'content/page/'.$Page->PageID.'/'.CleanupString($Page->Title));?></h4>

<ul class="PanelInfo">
	<?php if ($PermissionEdit): ?>
	<li><strong><?php 
		echo Anchor(T('Published'), 'candy/page/visible/'.$Page->PageID.'?Target='.$TargetUrl, 'BoolButton'); ?>
		<?php echo T($Page->Visible == 1 ? 'Yes' : 'No'); ?></strong></li>
	<li><strong><?php 
		echo Anchor(T('Edit'), 'candy/page/edit/'.$Page->PageID, ''); 
		?></strong>&#160;</li>
	<?php endif; ?>
	
	<?php if (CheckPermission('Candy.Pages.Delete')) : ?>
		<li><strong><?php 
			echo Anchor(T('Delete'), 'candy/page/delete/'.$Page->PageID, 'PopConfirm'); 
		?></strong>&#160;</li>
	<?php endif; ?>
</ul>
