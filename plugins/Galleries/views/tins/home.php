<?php if (!defined('APPLICATION')) exit();
include(PATH_PLUGINS.DS.'Galleries/views/helper/helper.php');




?>
<div id="Custom">
<?php
    ?><ul class="Home Gallery Image">
        <?php
        foreach ($Categories as $Category) {
            $Label = $Category->CategoryLabel;
            if ($Label != $ActiveCategory) {
            echo '<li'.($Sender->RequestMethod == '' ? ' class="Gallery Category"' : '').'><strong>'
			.Anchor(T($Label), 'plugin/gallery/'.$ActiveClass.DS.$Label, 'TabButton Category')
		.'</strong><span class="Count">'.count(GalleriesModel::GetFilesInfo($ActiveClass, $Label)).'</span></li>';
        }}?>
    </ul></div>