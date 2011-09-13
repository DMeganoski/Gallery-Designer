<?php if (!defined('APPLICATION'))
    exit();
include (PATH_PLUGINS.DS.'Galleries/views/helper/helper.php');
//echo $Sender->Form->Open();
//echo $Sender->Form->Errors();
?>
<div id="Custom">
    <h1>Sorry! The Details have not been filled in yet.</h1>
    <p>The tin-bots specialize in the details, though. Check back soon!</p>
    
    <ul id="Item">
            <?php //foreach ($AllFiles as $Class) {
                //print_r (GalleriesModel::GetFilesInfo('covers'));
                //print_r($Class);
                foreach ($Classes as $Class) {
                    $Label = $Class->ClassLabel;
                    $AllStuff = GalleriesModel::GetFilesInfo($Label);
                    foreach ($AllStuff as $File) {
                        echo '<a href="/tinsdirect/plugin/gallery/item/'.$File['Slug'].'">
                            <li><img src="'.$PublicDir.$Label.DS.$File['FileName'].'"></img></li>';
                        echo '</a>';
                    }
                } ?>
    </ul>
</div>
