<?php if (!defined('APPLICATION')) exit();
// This is the single Item page
include (PATH_APPLICATIONS.DS.'galleries/views/helper/helper.php');
//echo $Sender->Form->Open();
//echo $Sender->Form->Errors();
$FileInfo = $this->FileData;

?>
<div id="Custom">
	<div class="Large Picture">
		<div class="Heading">
			<h1><?php echo $FileInfo->Name.' ('.$FileInfo->Slug.')'; ?></h1>
			<h2>You choose a frame if you like, then drag the image to your project box</h2>
		</div><?php
			echo "<div class=\"Verify\"></div>";
			echo '<table><tr>';
			echo '<td>Description: <td>';
			echo '<td>'.$FileInfo->Description.'</td>';
		echo '</tr></table>';
			if (CheckPermission('Gallery.Items.Manage')) {
			echo '<li class="Popup">';
			echo '<a href="/item/edit/'.$FileInfo->Slug.'" class="NonTab Button">Edit</a></li>';
			}
			?>
		<div class="DetailsWrapper" itemtype="<? echo $FileInfo->ClassLabel ?>" itemslug="<? echo $FileInfo->Slug ?>">
			<?php
		echo '<div id="ImageWrapper"  file="'.$PublicDir.$FileInfo->ClassLabel.DS.$FileInfo->FileName.'">';
		echo '<div id="FrameWrapper"></div>';
		echo '<img src="'.$PublicDir.$FileInfo->ClassLabel.DS.$FileInfo->Slug.'L.jpg" class="Single"></img>';
		echo '</div>';
		include(PATH_APPLICATIONS.DS.'galleries/customfiles/detailedinfo.php');
		?>
		</div>
	</div><div class="ClearFix"></div>
<center><button type="button" name="pjSubmit" id="pjSubmit" onclick="" class="Selection Button">Use this in your current project</button></center>
<center><button type="button" name="pjSubmitNew" id="pjSubmitNew" onclick="" class="NewSelection Button">Use this in a new project</button></center>
	</div><?php
 if (C('Galleries.ShowFireEvents'))
			$this->DisplayFireEvent('AfterItemDetails');

	$this->FireEvent('AfterItemDetails');
$FileInfo = $this->FileData;
if (ItemController::$SelectedClass == 'covers') { ?>
	<div class="Heading">
		<h1>Preview Frame Options</h1>
	</div>
	<div>
		<ul id="Frames">
			<li id="Click" class="Button None Active" type="None">No Frame</li>
			<li id="Click" class="Button Pewter" type="Pewter">Pewter Frame</li>
			<li id="Click" class="Button GoldEmblem" type="GoldEmblem">Gold Emblem Frame</li>
			<li id="Click" class="Button Traditional" type="Traditional">Traditional Frame</li>
			<li id="Click" class="Button Modern" Type="Modern">Modern Frame</li>
			<li id="Click" class="Button Wood" type="Wood">Wood Frame</li>
			<li id="Click" class="Button GoldLeaf" type="GoldLeaf">Gold Leaf Frame</li>
		</ul>
		<?php
		echo '</div>';
} else {
	echo $FileInfo->ClassLabel;
}

