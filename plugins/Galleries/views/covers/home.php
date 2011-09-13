<?php if (!defined('APPLICATION')) exit();
include_once(PATH_PLUGINS.DS.'Galleries/views/helper/helper.php');




?>
<div id="Custom">
<?php
if ($ActiveCategory == 'home') {
    ?><ul class="Home Gallery Image">
        <?php
        foreach ($Categories as $Category) {
            $Label = $Category->CategoryLabel;
            if ($Label != $ActiveCategory) {
            echo '<li'.($Sender->RequestMethod == '' ? ' class="Gallery Category"' : '').'><a href="/tinsdirect/plugin/gallery/'.$ActiveClass.DS.$Label.'"><img src="'.$PublicDir.$ActiveClass.DS.$Label.'.jpg" class="Gallery Category"></img>
			</a><span class="Count">'.count(GalleriesModel::GetFilesInfo($ActiveClass, $Label)).' Total</span></li>';
        }}?>
    </ul></div><?php
} else {
    if (!is_array($AllFiles)) {
echo '<h1>error, no items found</h1></div>';
    } else {
?>
<ul class="Gallery">
    <?php
    foreach($LimitedFiles as $Item){
            ?><li class ="Item Gallery Image">
                <a href="<?php
                    echo '/tinsdirect/plugin/gallery/item'.DS.$ActiveClass.DS.$Item['Slug'];
                ?>">
                <img src="<?php
                echo GalleriesPlugin::$PublicDir.$ActiveClass.DS.$Item['FileName'];
                ?>" Class="Gallery Image"></img></a><?php
                echo '<div class="Label">';
                if ($Item['Name'] != NULL) {
                    echo trim($Item['Name'], '.jpg');
                }
                echo '</div>';
                ?></li><?php
                }?>
</ul>
</div>
<?php include(PATH_PLUGINS.DS.'Galleries/views/helper/pager.php');
    }
}