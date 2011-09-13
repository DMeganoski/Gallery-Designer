<?php if (!defined('APPLICATION')) exit();
include(PATH_PLUGINS.DS.'Galleries/views/helper/helper.php');
?>

<div id="Custom">
<ul class="Gallery">
    <?php 
    foreach($LimitedFiles as $Item){
            ?><li class ="Item Gallery Image">
                <a href="<?php
                    echo '/tinsdirect/plugin/gallery/item'.DS.$ActiveClass.DS.GalleriesPlugin::$SelectedFile['Category'].GalleriesPlugin::$SelectedFile['ItemID'];
                ?>">
                <img src="<?php
                echo GalleriesModel::$PublicDir.$ActiveClass.DS.$Item['FileName'];
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
