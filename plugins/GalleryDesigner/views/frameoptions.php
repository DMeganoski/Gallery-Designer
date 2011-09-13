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
      <?php
		echo '</div>';
} else {
	echo $FileInfo->ClassLabel;
}
?>
