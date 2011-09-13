<?php if (!defined('APPLICATION'))
   exit();
include(PATH_PLUGINS.DS.'Galleries/views/helper/helper.php');


?><div class="Custom"><?php
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