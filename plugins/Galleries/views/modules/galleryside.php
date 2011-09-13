<?php if (!defined('APPLICATION')) exit();
include(PATH_PLUGINS.DS.'Galleries/views/helper/helper.php');
$ClassCats = $this->GetCategories($ActiveClass);

if (CheckPermission('Gallery.Docs.Download')) {
    echo Anchor('Download a Template', '/plugin/gallery/templates', 'BigButton');
} ?>
<div class="Box BoxCategories">
    <h4><a href="/tinsdirect/categories/all">Categories</a></h4>
    <ul class="PanelInfo PanelCategories">
        <?php
        foreach ($ClassCats as $Category) {
	    $Label = $Category->CategoryLabel;
	    if ($Category->Visible == '1') {
		if ($Label == $ActiveCategory) {
		    $CSS = 'Active';
		} else {
		    $CSS = 'Depth';
		}
		echo '<li'.($Sender->RequestMethod == '' ? ' class="Gallery Images '.$CSS.'"' : '').'><strong>'
			.Anchor(T($Label), 'plugin/gallery/'.$ActiveClass.DS.$Label, 'TabButton Category')
		.'</strong><span class="Count">'.count(GalleriesModel::GetFilesInfo($ActiveClass, $Label)).'</span></li>';
	    }
	}?>
    </ul>
</div>
<?php
if (CheckPermission('Gallery.Item.Add')) {
    echo '<div class="Box DownloadPanelBox">';
    echo '<h4>Use your own images</h4>';
    echo Anchor(T('Upload Image'), '/plugin/gallery/upload', 'BigButton');
    echo '</div>';
} ?>
<div class="Box">
	<h4>Actions</h4>
	<ul>
	<?php
		$Session = Gdn::Session();

                  $Session = Gdn::Session();
                  $Permitted = $Session->CheckPermission('Gallery.Items.Add');
		echo '<li>'.Anchor('Quick-Start Guide', '/plugin/page/howitworks').'</li>';
		if ($Session->IsValid()) {
                    if (CheckPermission('Gallery.Items.Manage')) {
                        echo '<li>'.Anchor('Scan Folders For New Items','/gallery/scandir');
                    }
                     if (CheckPermission('Gallery.Items.Add')) {
			echo '<li>'.Anchor(T('Use your own image'), '/gallery/upload').'</li>';
                     }
		} else {
			echo '<li>'.Anchor('Sign In', Gdn::Authenticator()->SignInUrl('/gallery'), SignInPopup() ? 'SignInPopup' : '').'</li>';
		}
	?>
	</ul>
</div>

<div class="Box Items">
	<h4>Selected Items</h4>
	<img src="/tinsdirect/themes/TinsDirect/design/images/round_platinum.jpg" class="Chosen"></img>
        <img src="<?php
           echo $PublicDir.''
        ?>" class="Chosen"></img>
        <h4>Your selected items will go here.</h4>
</div>
