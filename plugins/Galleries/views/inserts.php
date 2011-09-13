<?php if (!defined('APPLICATION')) exit();
$Class = GalleriesModel::$Class;
$ClassDirectory = PATH_UPLOADS.DS.'item'.DS.$Class;
$Category = GalleriesModel::$Category;
$AllFiles = GalleriesModel::GetFilesInfo($ClassDirectory, $Category);
$Limit = GalleriesPlugin::$Limit;
$Offset = GalleriesPlugin::$Offset;
$LimitedFiles = array_slice($AllFiles, $Offset, $Limit, TRUE);
$OffsetLess = $Offset - $Limit;
$OffsetMore = $Offset + $Limit;
?>

<div id="Custom">
<ul class="Gallery">
    <?php 
    foreach($LimitedFiles as $Item){
            ?><li class ="Item Gallery Image">
                <a href="<?php
                    echo '/tinsdirect/plugin/gallery/item'.DS.$Class.DS.$Item['Category'].DS.$Item['ItemID'];
                ?>">
                <img src="<?php
                echo GalleriesModel::$PublicDir.$Class.DS.$Item['FileName'];
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
<div class="Pager">
        <?php
        if ($Offset != 0)
        echo '<a href="/tinsdirect/plugin/gallery/'.$Class.DS.$Category.DS.$OffsetLess.'" class="Less">Previous Page</a>';
        
        echo '<a href="/tinsdirect/plugin/gallery/'.$Class.DS.$Category.DS.$OffsetMore.'" class="More">Next Page</a>';        ?>
    </div>
