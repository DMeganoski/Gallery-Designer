<?php if (!defined('APPLICATION'))
    exit();


include(PATH_PLUGINS.'/Galleries/views/helper/helper.php');

$NextPage = ($Page + 1);
$PreviousPage = ($Page -1);
$OffsetLess = $Offset - $Limit;
$OffsetMore = $Offset + $Limit;
$Count = count($AllFiles);
$PageMax = ceil($Count / $Limit);


?>
<div class="Pager">
    <ul><?php
        if ($Offset != 0)
        echo '<li class="Less"><a href="/tinsdirect/plugin/gallery/'.$ActiveClass.DS.$ActiveCategory.DS.$PreviousPage.'" class="Less">Previous Page</a></li>';

        if ($Count > $Offset + $Limit)
        echo '<li class="More"><a href="/tinsdirect/plugin/gallery/'.$ActiveClass.DS.$ActiveCategory.DS.$NextPage.'">Next Page</a></li>';        ?>
    </ul>
</div>
<div class="Pager"><?php
    echo '<li class="Page">Page: <a href="'.'/tinsdirect/plugin/gallery/'.$ActiveClass.DS.$ActiveCategory.DS.$Page.'" class="Active">'.$Page.'</a> out of '.$PageMax.'</li>';

?></div>