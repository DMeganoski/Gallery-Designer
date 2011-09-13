<?php if (!defined('APPLICATION'))
	exit();
$FileInfo = $this->FileData;
if (ItemController::$SelectedClass == 'covers') { ?>
<div class="Heading">
      <h1>Preview Frame Options</h1>
</div>
<div>
	  <ul id="Frames">
		  <li id="Click" class="Button None Active">No Frame</li>
		  <li id="Click" class="Button Pewter">Pewter Frame</li>
		  <li id="Click" class="Button GoldEmblem">Gold Emblem Frame</li>
		  <li id="Click" class="Button Traditional">Traditional Frame</li>
		  <li id="Click" class="Button Modern">Modern Frame</li>
		  <li id="Click" class="Button Wood">Wood Frame</li>
		  <li id="Click" class="Button GoldLeaf">Gold Leaf Frame</li>



	  </ul>
      <?php if ($FileInfo->Frame != NULL) {
	 echo '<img src="'.$PublicDir.$FileInfo->ClassLabel.DS.$FileInfo->Frame.'"></img>'; }
		echo '</div>';
		echo '<ul><li><a href="/plugin/select/'.$FileInfo->ClassLabel.DS.$FileInfo->Slug.'">Choose this Item</a></li></ul>';
} else {
	echo $FileInfo->ClassLabel;
}
?></div>
</div>
