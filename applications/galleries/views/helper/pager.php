<?php if (!defined('APPLICATION'))
    exit();


include(PATH_APPLICATIONS.'/galleries/views/helper/helper.php');

?>
<div class="Pager">
    <ul><?php
        if ($Offset != 0)
        echo '<li class="Less Button"><a href="/gallery/'.$ActiveClass.DS.$ActiveCategory.DS.$PreviousPage.'" class="Less"> < Previous Page</a></li>';

        if ($Count > $Offset + $Limit)
        echo '<li class="More Button"><a href="/gallery/'.$ActiveClass.DS.$ActiveCategory.DS.$NextPage.'">Next Page > </a></li>';        ?>
    </ul>
</div>
<div class="Pager"><?php
   echo '<li class="Page">Page: ';
   if ($Page > 2)
      echo '<a href="/gallery/'.$ActiveClass.DS.$ActiveCategory.DS.($Page-2).'" class="Inactive">'.($Page-2).'</a>';
   if ($Page > 1)
      echo '<a href="/gallery/'.$ActiveClass.DS.$ActiveCategory.DS.($Page-1).'" class="Inactive">'.($Page-1).'</a>';
   echo '<a href="/gallery/'.$ActiveClass.DS.$ActiveCategory.DS.$Page.'" class="Active">'.$Page.'</a>';
   if ($Page < $PageMax)
      echo '<a href="/gallery/'.$ActiveClass.DS.$ActiveCategory.DS.($Page+1).'" class="Inactive">'.($Page+1).'</a>';
   if ($Page < $PageMax - 1)
      echo '<a href="/gallery/'.$ActiveClass.DS.$ActiveCategory.DS.($Page+2).'" class="Inactive">'.($Page+2).'</a>';
   echo 'out of '.$PageMax.'</li>';

?></div>