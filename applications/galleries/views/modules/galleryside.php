<?php if (!defined('APPLICATION')) exit();
$ActiveClass = GalleryController::$Class;
$PublicDir = GalleryController::$PublicDir;

$ActiveCategory = GalleryController::$Category;




//Categories Box

?><div class="Box BoxCategories">
    <h4 id="CategoryLabel">Categories</h4>
    <ul class="PanelInfo PanelCategories">
        <?php
        foreach ($ClassCats as $Category) {
			$Label = $Category->CategoryLabel;
			$CategoryKey = $Category->CategoryKey;
			if ($Category->Visible == '1') {
				if ($Label == $ActiveCategory) {
					$CSS = 'Active';
				} else {
					$CSS = 'Depth';
				}
				echo '<li'.($Sender->RequestMethod == '' ? ' class="Gallery Images '.$CSS.'"' : '').'><strong>'
					.Anchor(T($Label), 'gallery/'.$ActiveClass.DS.$Label, 'TabButton Category')
					.'</strong>';
				if ($this->GetCount(array('CategoryKey' => $CategoryKey)) > 0)
					echo '<span class="Count">'.$this->GetCount(array('CategoryKey' => $CategoryKey)).'</span></li>';
			}
		}?>
    </ul>
</div>

<div class="Box PanelActions">
	<h4 id="ActionLabel">Actions</h4>
	<ul>
	<?php
		$Session = Gdn::Session();
		if ($Session->IsValid()) {
			if ($Session->CheckPermission('Gallery.Items.Upload'))
				echo '<li>'.Anchor(T('Upload Image'), '/item/upload', 'BigButton').'</li>';
			if ($Session->CheckPermission('Gallery.Items.Manage'))
					echo '<li>'.Anchor(T('Scan Folders'),'/item/scan', 'BigButton').'</li>';
		} else {
			echo '<li>'.Anchor('Sign In', Gdn::Authenticator()->SignInUrl('/gallery'), SignInPopup() ? 'SignInPopup' : '').'</li>';
		}
		echo '<li>'.Anchor('Quick-Start Guide', '/plugin/page/howitworks').'</li>';

	if ($Session->CheckPermission('Gallery.Docs.Download')) {
		echo '<li>'.Anchor('Download a Template', '/gallery/templates', 'BigButton').'</li>';
	}
	?></ul>
</div>