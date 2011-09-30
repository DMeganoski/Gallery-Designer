<?php if (!defined('APPLICATION')) exit();

//Categories Box

?>

<div class="Box PanelActions">
	<h4 id="ToggleActions" class="Show Toggle">Downloads and Info</h4>
	<ul>
	<?php
		$Session = Gdn::Session();
		if ($Session->IsValid()) {
			if ($Session->CheckPermission('Gallery.Items.Manage'))
					echo '<li>'.Anchor(T('Scan Folders'),'/item/scan', 'BigButton').'</li>';
		} else {
			echo '<li>'.Anchor('Sign In', Gdn::Authenticator()->SignInUrl('/gallery'), SignInPopup() ? 'SignInPopup' : '').'</li>';
		}

	if ($Session->CheckPermission('Gallery.Docs.Download')) {
		echo '<li>'.Anchor('Download a Template', '/gallery/templates', 'BigButton').'</li>';
	}
	?>
</div>